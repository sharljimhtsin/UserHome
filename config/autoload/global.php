<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Session;

return [
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Session\Config\ConfigInterface'
            => 'Zend\Session\Service\SessionConfigFactory',
        ),
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
    ),
    'session_config' => [
        'remember_me_seconds' => 60 * 60,
        'cookie_lifetime' => 60 * 60,
    ],
    'session_manager' => [
        'config' => [
            'class' => Session\Config\SessionConfig::class,
            'options' => [
                'name' => 'main',
            ],
        ],
        'storage' => Session\Storage\SessionArrayStorage::class,
        'validators' => [
            Session\Validator\RemoteAddr::class,
            Session\Validator\HttpUserAgent::class,
        ],
    ],
    'session_storage' => [
        'type' => Session\Storage\SessionArrayStorage::class,
    ],
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=userHome;host=localhost',
        'username' => 'root',
        'password' => 'root',
        'adapters' => array(
            'db1' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=userHome1;host=localhost',
                'username' => 'root',
                'password' => 'root',
            ),
            'db2' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=userHome2;host=localhost',
                'username' => 'root',
                'password' => 'root',
            ),
        )
    ),
];
