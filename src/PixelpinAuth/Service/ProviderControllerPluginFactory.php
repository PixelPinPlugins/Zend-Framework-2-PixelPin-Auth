<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use PixelpinAuth\Controller\Plugin\PixelpinAuthProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class ProviderControllerPluginFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $mapper = $serviceManager->getServiceLocator()->get('PixelpinAuth-UserProviderMapper');

        $controllerPlugin = new PixelpinAuthProvider();
        $controllerPlugin->setMapper($mapper);

        return $controllerPlugin;
    }
}
