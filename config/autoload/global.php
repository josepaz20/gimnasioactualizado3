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
return array(
    'passwordSeguro' => 'aFGQ475SDsdfsaf2342',
    'db' => array(
        //this is for primary adapter....
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=gimnasiogymjam_bd;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        //other adapter when it needed...
        'adapters' => array(
            'dbjosandro_' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=gimnasiogymjam_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbjosandro_1' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=alpa_paicol_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbjosandro_2' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=alpa_paicol_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbjosandro_3' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=gimnasiogymjam_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbjosandro_4' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=paicol_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbjosandro_5' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=alpa_paicol_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
            'dbhotspot' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=hotspot_bd;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
