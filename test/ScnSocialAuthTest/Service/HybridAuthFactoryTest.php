<?php
/**
 * @link      https://github.com/SocalNick/PixelpinAuth for the canonical source repository
 * @copyright Copyright (c) 2012 Nicholas Calugar (http://socalnick.github.com)
 */

namespace PixelpinAuthTest\Service;

use PixelpinAuth\Service\HybridAuthFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Console\SimpleRouteStack;
use Zend\ServiceManager\ServiceManager;
use Zend\Uri\Http as HttpUri;

/**
 * Unit tests for HybridAuthFactory
 */
class HybridAuthFactoryTest extends TestCase
{
    /**
     * @var HybridAuthFactory
     */
    protected $factory;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->factory = new HybridAuthFactory;
        $this->serviceManager = new ServiceManager();
        $this->serviceManager->setService('Router', new TreeRouteStack());
        $this->serviceManager->setService('Request', new Request());
    }

    public function configureRoute()
    {
        $router = $this->serviceManager->get('Router');
        $router->addRoute(
            'pixelpin-auth-hauth',
            array(
                'type'    => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/pixelpin-auth/hauth',
                    'defaults' => array(
                        'controller' => 'PixelpinAuth-HybridAuth',
                        'action'     => 'index',
                    ),
                ),
            )
        );
    }

    public function testGetBaseUrlThrowsException()
    {
        $this->setExpectedException('Zend\Mvc\Router\Exception\RuntimeException', 'Route with name "pixelpin-auth-hauth" not found');
        $this->factory->getBaseUrl($this->serviceManager);
    }

    public function testUseRouterRequestUri()
    {
        $this->configureRoute();
        $httpUri = new HttpUri();
        $httpUri->setScheme('http');
        $httpUri->setHost('use-router-request-uri.com');
        $router = $this->serviceManager->get('Router');
        $router->setRequestUri($httpUri);
        $baseUrl = $this->factory->getBaseUrl($this->serviceManager);
        $this->assertEquals('http://use-router-request-uri.com/pixelpin-auth/hauth', $baseUrl);
    }

    public function testUseRouterBaseUrl()
    {
        $this->configureRoute();
        $httpUri = new HttpUri();
        $httpUri->setScheme('http');
        $httpUri->setHost('use-router-request-uri.com');
        $router = $this->serviceManager->get('Router');
        $router->setRequestUri($httpUri);
        $router->setBaseUrl('/some/base/url/');
        $baseUrl = $this->factory->getBaseUrl($this->serviceManager);
        $this->assertEquals('http://use-router-request-uri.com/some/base/url/pixelpin-auth/hauth', $baseUrl);
    }

    public function testUseRequestUri()
    {
        $this->configureRoute();
        $httpUri = new HttpUri();
        $httpUri->setScheme('http');
        $httpUri->setHost('use-request-uri.com');
        $request = $this->serviceManager->get('Request');
        $request->setUri($httpUri);
        $baseUrl = $this->factory->getBaseUrl($this->serviceManager);
        $this->assertEquals('http://use-request-uri.com/pixelpin-auth/hauth', $baseUrl);
    }

    public function testUseRequestBaseUrl()
    {
        $this->configureRoute();
        $httpUri = new HttpUri();
        $httpUri->setScheme('http');
        $httpUri->setHost('use-request-uri.com');
        $request = $this->serviceManager->get('Request');
        $request->setUri($httpUri);
        $request->setBaseUrl('/another/base/url/');
        $baseUrl = $this->factory->getBaseUrl($this->serviceManager);
        $this->assertEquals('http://use-request-uri.com/another/base/url/pixelpin-auth/hauth', $baseUrl);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testSimpleRouteStack()
    {
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setService('Router', new SimpleRouteStack());
        $this->factory->getBaseUrl($this->serviceManager);
    }
}
