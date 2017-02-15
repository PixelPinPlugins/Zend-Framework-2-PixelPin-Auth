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
use ZfcUserPixelpin\Authentication\Adapter\AdapterChainServiceFactory;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class AuthenticationAdapterChainFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        // Temporarily replace the adapters in the module options with the HybridAuth adapter
        $zfcUserModuleOptions = $services->get('zfcuser_module_options');
        $currentAuthAdapters = $zfcUserModuleOptions->getAuthAdapters();
        $zfcUserModuleOptions->setAuthAdapters(array(100 => 'PixelpinAuth\Authentication\Adapter\HybridAuth'));

        // Create a new adapter chain with HybridAuth adapter
        $factory = new AdapterChainServiceFactory();
        $chain = $factory->createService($services);

        // Reset the adapters in the module options
        $zfcUserModuleOptions->setAuthAdapters($currentAuthAdapters);

        return $chain;
    }
}
