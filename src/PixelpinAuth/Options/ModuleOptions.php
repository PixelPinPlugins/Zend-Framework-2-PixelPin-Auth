<?php

namespace PixelpinAuth\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;
	
	/**
	 * @var array 
	 */
    protected $providers = array(
        'PixelPin'
    );

    /**
     * @var string
     */
    protected $userProviderEntityClass = 'PixelpinAuth\Entity\UserProvider';

    /**
     * @var boolean
     */

    protected $PixelPinEnabled = false;

    /**
     * @var string
     */
    protected $PixelPinClientId;

    /**
     * @var string
     */
    protected $PixelPinSecret;

    /**
     * @var string
     */
    protected $PixelPinScope;

    /**
     * @var string
     */
    protected $PixelPinDisplay;

    /**
     * @var boolean
     */
    protected $PixelPinTrustForwarded;
    
    /**
     * @var string
     */
    protected $socialLoginOnly = false;

    /**
     * @var boolean
     */
    protected $enableSocialRegistration = true;

    /**
     * @var boolean
     */
    protected $debugMode = false;

    /**
     * @var boolean
     */
    protected $debugFile = "/tmp/hybridauth.log";

    /**
     * @var string
     */

    /**
     * get an array of enabled providers
     *
     * @return array
     */
    public function getEnabledProviders()
    {
        $enabledProviders = array();
        foreach ($this->providers as $provider) {
            $method = 'get' . $provider . 'Enabled';
            if ($this->$method()) {
                $enabledProviders[] = $provider;
            }
        }

        return $enabledProviders;
    }

    /**
     * set user provider entity class
     *
     * @param  string        $userProviderEntityClass
     * @return ModuleOptions
     */
    public function setUserProviderEntityClass($userProviderEntityClass)
    {
        $this->userProviderEntityClass = (string) $userProviderEntityClass;

        return $this;
    }

    /**
     * get user provider entity class
     *
     * @return string
     */
    public function getUserProviderEntityClass()
    {
        return $this->userProviderEntityClass;
    }

    /**
     * set PixelPin enabled
     *
     * @param  boolean       $PixelPinEnabled
     * @return ModuleOptions
     */
    public function setPixelPinEnabled($PixelPinEnabled)
    {
        $this->PixelPinEnabled = (boolean) $PixelPinEnabled;

        return $this;
    }

    /**
     * get PixelPin enabled
     *
     * @return boolean
     */
    public function getPixelPinEnabled()
    {
        return $this->PixelPinEnabled;
    }

    /**
     * set PixelPin client id
     *
     * @param  string        $PixelPinClientId
     * @return ModuleOptions
     */
    public function setPixelPinClientId($PixelPinClientId)
    {
        $this->PixelPinClientId = (string) $PixelPinClientId;

        return $this;
    }

    /**
     * get PixelPin client id
     *
     * @return string
     */
    public function getPixelPinClientId()
    {
        return $this->PixelPinClientId;
    }

    /**
     * set PixelPin secret
     *
     * @param  string        $PixelPinSecret
     * @return ModuleOptions
     */
    public function setPixelPinSecret($PixelPinSecret)
    {
        $this->PixelPinSecret = (string) $PixelPinSecret;

        return $this;
    }

    /**
     * get PixelPin secret
     *
     * @return string
     */
    public function getPixelPinSecret()
    {
        return $this->PixelPinSecret;
    }

    /**
     * set PixelPin scopes
     *
     * @param  string        $PixelPinScope
     * @return ModuleOptions
     */
    public function setPixelPinScope($PixelPinScope)
    {
        $this->PixelPinScope = (string) $PixelPinScope;

        return $this;
    }

    /**
     * get PixelPin scope
     *
     * @return string
     */
    public function getPixelPinScope()
    {
        return $this->PixelPinScope;
    }

    /**
     * set PixelPin display
     *
     * @param  string        $PixelPinDisplay
     * @return ModuleOptions
     */
    public function setPixelPinDisplay($PixelPinDisplay)
    {
        $this->PixelPinDisplay = (string) $PixelPinDisplay;

        return $this;
    }

    /**
     * get PixelPin display
     *
     * @return string
     */
    public function getPixelPinDisplay()
    {
        return $this->PixelPinDisplay;
    }
	
	/**
	 * set PixelPin trust forwarded.
	 * 
	 * @param type $PixelPinTrustForwarded
	 * @return $this
	 */
    public function setPixelPinTrustForwarded($PixelPinTrustForwarded)
    {
        $this->PixelPinTrustForwarded = $PixelPinTrustForwarded;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getPixelPinTrustForwarded()
    {
        return $this->PixelPinTrustForwarded;
    }


    /**
     * get social login only
     *
     * @return string
     */
    public function getSocialLoginOnly()
    {
        return $this->socialLoginOnly;
    }

    /**
     * Sets enableSocialRegistration
     *
     * @param bool $enableSocialRegistration
     *
     * @return void
     */
    public function setEnableSocialRegistration($enableSocialRegistration)
    {
        $this->enableSocialRegistration = (bool) $enableSocialRegistration;
    }

    /**
     * Gets enableSocialRegistration
     *
     * @return bool
     */
    public function getEnableSocialRegistration()
    {
        return $this->enableSocialRegistration;
    }

    /**
     * @return boolean
     */
    public function getDebugMode()
    {
        return $this->debugMode;
    }

    /**
     * @param boolean $debugMode
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = (boolean) $debugMode;
    }

    /**
     * @return string
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }

    /**
     * @param string $debugFile
     */
    public function setDebugFile($debugFile)
    {
        $this->debugFile = (string) $debugFile;
    }

    /**
     * @return boolean
     */
}
