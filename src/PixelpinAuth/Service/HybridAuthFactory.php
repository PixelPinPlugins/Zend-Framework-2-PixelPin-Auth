<?php
/**
 * PixelpinAuth Module
 *
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */

namespace PixelpinAuth\Service;

use Hybrid_Auth;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   PixelpinAuth
 * @package    PixelpinAuth_Service
 */
class HybridAuthFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        // Making sure the SessionManager is initialized
        // before creating HybridAuth components
        $sessionManager = $services->get('PixelpinAuth_ZendSessionManager')->start();

        /* @var $options \PixelpinAuth\Options\ModuleOptions */
        $options = $services->get('PixelpinAuth-ModuleOptions');

        $baseUrl = $this->getBaseUrl($services);

        $hybridAuth = new Hybrid_Auth(
            array(
                'base_url' => $baseUrl,
                "debug_mode" => $options->getDebugMode(),
                "debug_file" => $options->getDebugFile(),
                'providers' => array(
                    'PixelPin' => array(
                        'enabled' => $options->getPixelPinEnabled(),
                        'keys' => array(
                            'id' => $options->getPixelPinClientId(),
                            'secret' => $options->getPixelPinSecret(),
                        ),
                        'scope' => $options->getPixelPinScope(),
                        'display' => $options->getPixelPinDisplay(),
                        'trustForwarded' => $options->getPixelPinTrustForwarded(),
                    ),
                ),
            )
        );

        return $hybridAuth;
    }

    public function getBaseUrl(ServiceLocatorInterface $services)
    {
        $router = $services->get('Router');
        if (!$router instanceof TreeRouteStack) {
            throw new ServiceNotCreatedException('TreeRouteStack is required to create a fully qualified base url for HybridAuth');
        }

        $request = $services->get('Request');
        if (!$router->getRequestUri() && method_exists($request, 'getUri')) {
            $router->setRequestUri($request->getUri());
        }
        if (!$router->getBaseUrl() && method_exists($request, 'getBaseUrl')) {
            $router->setBaseUrl($request->getBaseUrl());
        }

        return $router->assemble(
            array(),
            array(
                'name' => 'pixelpin-auth-hauth',
                'force_canonical' => true,
            )
        );
    }
}
