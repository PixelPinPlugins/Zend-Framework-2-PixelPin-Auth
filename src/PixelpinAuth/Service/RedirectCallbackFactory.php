<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use PixelpinAuth\Controller\RedirectCallback;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class RedirectCallbackFactory implements FactoryInterface
{
  public function createService(ServiceLocatorInterface $serviceLocator)
  {
    $router = $serviceLocator->get('Router');
    $application = $serviceLocator->get('Application');
    $options = $serviceLocator->get('zfcuser_module_options');

    return new RedirectCallback($application, $router, $options);
  }
}
