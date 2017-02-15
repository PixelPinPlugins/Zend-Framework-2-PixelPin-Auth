<?php
/**
 * PixelpinAuth Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    
    'pixelpin_enabled' => false,

);

return array(
    'pixelpin-auth' => $settings,
    'service_manager' => array(
        'aliases' => array(
            'PixelpinAuth_ZendDbAdapter' => (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter']: 'Zend\Db\Adapter\Adapter',
            'PixelpinAuth_ZendSessionManager' => (isset($settings['zend_session_manager'])) ? $settings['zend_session_manager']: 'Zend\Session\SessionManager',
        ),
    ),
);
