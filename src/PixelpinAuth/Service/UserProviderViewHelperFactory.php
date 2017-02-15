<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class UserProviderViewHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $viewHelper = new \PixelpinAuth\View\Helper\ScnUserProvider();
        $viewHelper->setUserProviderMapper($serviceLocator->get('PixelpinAuth-UserProviderMapper'));

        return $viewHelper;
    }
}
