<?php

namespace PixelpinAuth\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

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
     * set facebook enabled
     *
     * @param  boolean       $facebookEnabled
     * @return ModuleOptions
     */

    public function setPixelPinEnabled($PixelPinEnabled)
    {
        $this->PixelPinEnabled = (boolean) $PixelPinEnabled;

        return $this;
    }

    /**
     * get facebook enabled
     *
     * @return boolean
     */
    public function getPixelPinEnabled()
    {
        return $this->PixelPinEnabled;
    }

    /**
     * set facebook client id
     *
     * @param  string        $facebookClientId
     * @return ModuleOptions
     */
    public function setPixelPinClientId($PixelPinClientId)
    {
        $this->PixelPinClientId = (string) $PixelPinClientId;

        return $this;
    }

    /**
     * get facebook client id
     *
     * @return string
     */
    public function getPixelPinClientId()
    {
        return $this->PixelPinClientId;
    }

    /**
     * set facebook secret
     *
     * @param  string        $facebookSecret
     * @return ModuleOptions
     */
    public function setPixelPinSecret($PixelPinSecret)
    {
        $this->PixelPinSecret = (string) $PixelPinSecret;

        return $this;
    }

    /**
     * get facebook secret
     *
     * @return string
     */
    public function getPixelPinSecret()
    {
        return $this->PixelPinSecret;
    }

    /**
     * set facebook scope
     *
     * @param  string        $facebookScope
     * @return ModuleOptions
     */
    public function setPixelPinScope($PixelPinScope)
    {
        $this->PixelPinScope = (string) $PixelPinScope;

        return $this;
    }

    /**
     * get facebook scope
     *
     * @return string
     */
    public function getPixelPinScope()
    {
        return $this->PixelPinScope;
    }

    /**
     * set facebook display
     *
     * @param  string        $facebookDisplay
     * @return ModuleOptions
     */
    public function setPixelPinDisplay($PixelPinDisplay)
    {
        $this->PixelPinDisplay = (string) $PixelPinDisplay;

        return $this;
    }

    /**
     * get facebook display
     *
     * @return string
     */
    public function getPixelPinDisplay()
    {
        return $this->PixelPinDisplay;
    }

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
     * set foursquare enabled
     *
     * @param  boolean       $foursquareEnabled
     * @return ModuleOptions
     */


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
