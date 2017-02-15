<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use PixelpinAuth\Authentication\Adapter\HybridAuth as HybridAuthAdapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class HybridAuthAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $moduleOptions = $services->get('PixelpinAuth-ModuleOptions');
        $zfcUserOptions = $services->get('zfcuser_module_options');

        $mapper = $services->get('PixelpinAuth-UserProviderMapper');
        $zfcUserMapper = $services->get('zfcuser_user_mapper');

        $hybridAuth = $services->get('HybridAuth');

        $adapter = new HybridAuthAdapter();
        $adapter->setOptions($moduleOptions);
        $adapter->setZfcUserPixelpinOptions($zfcUserOptions);
        $adapter->setMapper($mapper);
        $adapter->setZfcUserPixelpinMapper($zfcUserMapper);
        $adapter->setHybridAuth($hybridAuth);

        return $adapter;
    }
}
