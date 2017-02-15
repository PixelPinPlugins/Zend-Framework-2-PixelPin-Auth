<?php
return array(
    'controllers' => array(
        'factories' => array(
            'PixelpinAuth-HybridAuth' => 'PixelpinAuth\Service\HybridAuthControllerFactory',
            'PixelpinAuth-User' => 'PixelpinAuth\Service\UserControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'pixelpinAuthProvider' => 'PixelpinAuth\Service\ProviderControllerPluginFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'pixelpin-auth-hauth' => array(
                'type'    => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/pixelpin-auth/hauth',
                    'defaults' => array(
                        'controller' => 'PixelpinAuth-HybridAuth',
                        'action'     => 'index',
                    ),
                ),
            ),
            'pixelpin-auth-user' => array(
                'type' => 'Literal',
                'priority' => 2000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'PixelpinAuth-User',
                                        'action' => 'provider-authenticate',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'PixelpinAuth-User',
                                'action'     => 'login',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'PixelpinAuth-User',
                                        'action' => 'provider-login',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'PixelpinAuth-User',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'PixelpinAuth-User',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'add-provider' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add-provider',
                            'defaults' => array(
                                'controller' => 'PixelpinAuth-User',
                                'action'     => 'add-provider',
                            ),
                        ),
                        'child_routes' => array(
                            'provider' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:provider',
                                    'constraints' => array(
                                        'provider' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'PixelpinAuth_ZendDbAdapter' => 'Zend\Db\Adapter\Adapter',
            'PixelpinAuth_ZendSessionManager' => 'Zend\Session\SessionManager',
        ),
        'factories' => array(
            'HybridAuth' => 'PixelpinAuth\Service\HybridAuthFactory',
            'PixelpinAuth-ModuleOptions' => 'PixelpinAuth\Service\ModuleOptionsFactory',
            'PixelpinAuth-UserProviderMapper' => 'PixelpinAuth\Service\UserProviderMapperFactory',
            'PixelpinAuth-AuthenticationAdapterChain' => 'PixelpinAuth\Service\AuthenticationAdapterChainFactory',
            'PixelpinAuth\Authentication\Adapter\HybridAuth' => 'PixelpinAuth\Service\HybridAuthAdapterFactory',
            'zfcuser_redirect_callback' => 'PixelpinAuth\Service\RedirectCallbackFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'socialSignInButton' => 'PixelpinAuth\View\Helper\SocialSignInButton',
        ),
        'factories' => array(
            'scnUserProvider'   => 'PixelpinAuth\Service\UserProviderViewHelperFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'pixelpin-auth' => __DIR__ . '/../view'
        ),
    ),
);
