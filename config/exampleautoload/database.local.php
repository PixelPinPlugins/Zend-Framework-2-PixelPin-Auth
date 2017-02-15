<?php

$dbParams = array(
    'database'  => '-DB Name-',
    'username'  => '-DB username-',
    'password'  => '-DB Password-',
    'hostname'  => '-DB Hostname-',
    //'port'      => port if needed,
);

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                return new Zend\Db\Adapter\Adapter(array(
                    'driver'    => 'pdo',
                    'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'].';port='.$dbParams['port'],
                    'database'  => $dbParams['database'],
                    'username'  => $dbParams['username'],
                    'password'  => $dbParams['password'],
                    'hostname'  => $dbParams['hostname'],
                    'port'      => $dbParams['port'],
                ));
            },
        ),
    ),
);