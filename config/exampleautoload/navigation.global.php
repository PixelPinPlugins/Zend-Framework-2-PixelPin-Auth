<?php
return array(
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Login',
                'route' => 'pixelpin-auth-user/login',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
);