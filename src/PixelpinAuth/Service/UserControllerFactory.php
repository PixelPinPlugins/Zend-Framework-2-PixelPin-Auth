<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use PixelpinAuth\Controller\UserController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class UserControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $mapper = $controllerManager->getServiceLocator()->get('PixelpinAuth-UserProviderMapper');
        $moduleOptions = $controllerManager->getServiceLocator()->get('PixelpinAuth-ModuleOptions');
        $redirectCallback = $controllerManager->getServiceLocator()->get('zfcuser_redirect_callback');
        $zfcuserModuleOptions = $controllerManager->getServiceLocator()->get('zfcuser_module_options');
        $scnAuthAdapterChain = $controllerManager->getServiceLocator()->get('PixelpinAuth-AuthenticationAdapterChain');

        $controller = new UserController($redirectCallback);
        $controller->setMapper($mapper);
        $controller->setOptions($moduleOptions);
        $controller->setZfcModuleOptions($zfcuserModuleOptions);
        $controller->setScnAuthAdapterChain($scnAuthAdapterChain);

        try {
          $hybridAuth = $controllerManager->getServiceLocator()->get('HybridAuth');
          $controller->setHybridAuth($hybridAuth);
        } catch (\Zend\ServiceManager\Exception\ServiceNotCreatedException $e) {
          // This is likely the user cancelling login...
        }

        return $controller;
    }
}
