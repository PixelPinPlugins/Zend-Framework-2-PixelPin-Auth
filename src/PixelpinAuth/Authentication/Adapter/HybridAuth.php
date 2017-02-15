<?php

namespace PixelpinAuth\Authentication\Adapter;

use Hybrid_Auth;
use PixelpinAuth\Mapper\UserProviderInterface;
use PixelpinAuth\Options\ModuleOptions;
use Zend\Authentication\Result;
use ZfcUserPixelpin\Authentication\Adapter\AbstractAdapter;
use ZfcUserPixelpin\Authentication\Adapter\AdapterChainEvent as AuthEvent;
use ZfcUserPixelpin\Entity\UserInterface;
use ZfcUserPixelpin\Mapper\UserInterface as UserMapperInterface;
use ZfcUserPixelpin\Options\UserServiceOptionsInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class HybridAuth extends AbstractAdapter implements EventManagerAwareInterface
{
    /**
     * @var Hybrid_Auth
     */
    protected $hybridAuth;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $zfcUserOptions;

    /**
     * @var UserProviderInterface
     */
    protected $mapper;

    /**
     * @var UserMapperInterface
     */
    protected $zfcUserMapper;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    public function authenticate(AuthEvent $authEvent)
    {
        if ($this->isSatisfied()) {
            $storage = $this->getStorage()->read();
            $authEvent->setIdentity($storage['identity'])
              ->setCode(Result::SUCCESS)
              ->setMessages(array('Authentication successful.'));

            return;
        }

        $enabledProviders = $this->getOptions()->getEnabledProviders();
        $provider = $authEvent->getRequest()->getMetadata('provider');

        if (empty($provider) || !in_array($provider, $enabledProviders)) {
            $authEvent->setCode(Result::FAILURE)
              ->setMessages(array('Invalid provider'));
            $this->setSatisfied(false);

            return false;
        }

        try {
            $hybridAuth = $this->getHybridAuth();
            $adapter = $hybridAuth->authenticate($provider);
            $userProfile = $adapter->getUserProfile();
        } catch (\Exception $ex) {
            $authEvent->setCode(Result::FAILURE)
              ->setMessages(array('Invalid provider'));
            $this->setSatisfied(false);

            return false;
        }

        if (!$userProfile) {
            $authEvent->setCode(Result::FAILURE_IDENTITY_NOT_FOUND)
              ->setMessages(array('A record with the supplied identity could not be found.'));
            $this->setSatisfied(false);

            return false;
        }

        $localUserProvider = $this->getMapper()->findUserByProviderId($userProfile->identifier, $provider);
        if (false == $localUserProvider) {
            if (!$this->getOptions()->getEnableSocialRegistration()) {
                $authEvent->setCode(Result::FAILURE_IDENTITY_NOT_FOUND)
                  ->setMessages(array('A record with the supplied identity could not be found.'));
                $this->setSatisfied(false);

                return false;
            }
            $method = $provider.'ToLocalUser';
            if (method_exists($this, $method)) {
                try {
                    $localUser = $this->$method($userProfile);
                } catch (Exception\RuntimeException $ex) {
                    $authEvent->setCode($ex->getCode())
                        ->setMessages(array($ex->getMessage()))
                        ->stopPropagation();
                    $this->setSatisfied(false);

                    return false;
                }
            } else {
                $localUser = $this->instantiateLocalUser();
                $localUser->setDisplayName($userProfile->displayName)
                          ->setPassword($provider);
                if (isset($userProfile->emailVerified) && !empty($userProfile->emailVerified)) {
                    $localUser->setEmail($userProfile->emailVerified);
                }
                $result = $this->insert($localUser, $provider, $userProfile);
            }
            $localUserProvider = clone($this->getMapper()->getEntityPrototype());
            $localUserProvider->setUserId($localUser->getId())
                ->setProviderId($userProfile->identifier)
                ->setProvider($provider);

            // Trigger register.pre event
            $this->getEventManager()->trigger('register.pre', $this, array('user' => $localUser, 'userProvider' => $localUserProvider, 'userProfile' => $userProfile));

            $this->getMapper()->insert($localUserProvider);

            // Trigger register.post event
            $this->getEventManager()->trigger('register.post', $this, array('user' => $localUser, 'userProvider' => $localUserProvider));
        } else {
            $mapper = $this->getZfcUserPixelpinMapper();
            $localUser = $mapper->findById($localUserProvider->getUserId());

            if ($localUser instanceof UserInterface) {
                $this->update($localUser, $provider, $userProfile);
            }
        }

        $zfcUserOptions = $this->getZfcUserPixelpinOptions();

        if ($zfcUserOptions->getEnableUserState()) {
            // Don't allow user to login if state is not in allowed list
            $mapper = $this->getZfcUserPixelpinMapper();
            $user = $mapper->findById($localUserProvider->getUserId());
            if (!in_array($user->getState(), $zfcUserOptions->getAllowedLoginStates())) {
                $authEvent->setCode(Result::FAILURE_UNCATEGORIZED)
                  ->setMessages(array('A record with the supplied identity is not active.'));
                $this->setSatisfied(false);

                return false;
            }
        }

        $authEvent->setIdentity($localUserProvider->getUserId());

        $this->setSatisfied(true);
        $storage = $this->getStorage()->read();
        $storage['identity'] = $authEvent->getIdentity();
        $this->getStorage()->write($storage);
        $authEvent->setCode(Result::SUCCESS)
          ->setMessages(array('Authentication successful.'));
    }

    /**
     * Get the Hybrid_Auth object
     *
     * @return Hybrid_Auth
     */
    public function getHybridAuth()
    {
        return $this->hybridAuth;
    }

    /**
     * Set the Hybrid_Auth object
     *
     * @param  Hybrid_Auth    $hybridAuth
     * @return UserController
     */
    public function setHybridAuth(Hybrid_Auth $hybridAuth)
    {
        $this->hybridAuth = $hybridAuth;

        return $this;
    }

    /**
     * set options
     *
     * @param  ModuleOptions $options
     * @return HybridAuth
     */
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param  UserServiceOptionsInterface $options
     * @return HybridAuth
     */
    public function setZfcUserPixelpinOptions(UserServiceOptionsInterface $options)
    {
        $this->zfcUserOptions = $options;

        return $this;
    }

    /**
     * @return UserServiceOptionsInterface
     */
    public function getZfcUserPixelpinOptions()
    {
        return $this->zfcUserOptions;
    }

    /**
     * set mapper
     *
     * @param  UserProviderInterface $mapper
     * @return HybridAuth
     */
    public function setMapper(UserProviderInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return UserProviderInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * set zfcUserMapper
     *
     * @param  UserMapperInterface $zfcUserMapper
     * @return HybridAuth
     */
    public function setZfcUserPixelpinMapper(UserMapperInterface $zfcUserMapper)
    {
        $this->zfcUserMapper = $zfcUserMapper;

        return $this;
    }

    /**
     * get zfcUserMapper
     *
     * @return UserMapperInterface
     */
    public function getZfcUserPixelpinMapper()
    {
        return $this->zfcUserMapper;
    }

    /**
     * Utility function to instantiate a fresh local user object
     *
     * @return mixed
     */
    protected function instantiateLocalUser()
    {
        $userModelClass = $this->getZfcUserPixelpinOptions()->getUserEntityClass();

        return new $userModelClass;
    }

    // Provider specific methods

    /**
     * BitBucket to Local User
     * @param $userProfile
     * @return mixed
     */
    protected function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

    protected function PixelPinToLocalUser($userProfile)
    {
        if (!isset($userProfile->emailVerified) || empty($userProfile->emailVerified)) {
            throw new Exception\RuntimeException(
                'Please verify your email with PixelPin before attempting login',
                Result::FAILURE_CREDENTIAL_INVALID
            );
        }
        $mapper = $this->getZfcUserPixelpinMapper();
        if (false != ($localUser = $mapper->findByEmail($userProfile->emailVerified))) {
            return $localUser;
        }
        $localUser = $this->instantiateLocalUser();
        $localUser->setEmail($userProfile->emailVerified)
            ->setDisplayName($userProfile->displayName)
            ->setEmail($userProfile->email)
            ->setFirstName($userProfile->firstName)
            ->setLastName($userProfile->lastName)
            ->setNickName($userProfile->nickname)
            ->setGender($userProfile->gender)
            ->setBirthdate($userProfile->birthdate)
            ->setPhoneNumber($userProfile->phoneNumber)
            ->setAddress($userProfile->address)
            ->setCountry($userProfile->country)
            ->setRegion($userProfile->region)
            ->setCity($userProfile->city)
            ->setZip($userProfile->zip)
            ->setPassword($this->generateRandomString());
        $result = $this->insert($localUser, 'PixelPin', $userProfile);

        return $localUser;
    }

    /**
     * persists the user in the db, and trigger a pre and post events for it
     * @param  mixed  $user
     * @param  string $provider
     * @param  mixed  $userProfile
     * @return mixed
     */
    protected function insert($user, $provider, $userProfile)
    {
        $zfcUserOptions = $this->getZfcUserPixelpinOptions();

        // If user state is enabled, set the default state value
        if ($zfcUserOptions->getEnableUserState()) {
            if ($zfcUserOptions->getDefaultUserState()) {
                $user->setState($zfcUserOptions->getDefaultUserState());
            }
        }

        $options = array(
            'user'          => $user,
            'provider'      => $provider,
            'userProfile'   => $userProfile,
        );

        $this->getEventManager()->trigger('registerViaProvider', $this, $options);
        $result = $this->getZfcUserPixelpinMapper()->insert($user);
        $this->getEventManager()->trigger('registerViaProvider.post', $this, $options);

        return $result;
    }

    /**
     * @param  UserInterface        $user
     * @param  string               $provider
     * @param  \Hybrid_User_Profile $userProfile
     * @return mixed
     */
    protected function update(UserInterface $user, $provider, \Hybrid_User_Profile $userProfile)
    {
        $user->setDisplayName($userProfile->displayName);
        $user->setEmail($userProfile->email);

        $options = array(
            'user'          => $user,
            'provider'      => $provider,
            'userProfile'   => $userProfile,
        );

        $this->getEventManager()->trigger('scnUpdateUser.pre', $this, $options);
        $result = $this->getZfcUserPixelpinMapper()->update($user);
        $this->getEventManager()->trigger('scnUpdateUser.post', $this, $options);

        return $result;
    }

    /**
     * Set Event Manager
     *
     * @param  EventManagerInterface $events
     * @return HybridAuth
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;

        return $this;
    }

    /**
     * Get Event Manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }
}
