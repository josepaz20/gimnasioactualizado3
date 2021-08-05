<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TalentoHumano;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        global $database;
        $auth = new AuthenticationService();
        $storage = $auth->getStorage();
        $sesionUsuario = $storage->read();
        if ($sesionUsuario != NULL) {
            if (property_exists($sesionUsuario, 'idSucursalLogin')) {
                if ($sesionUsuario->idSucursalLogin > 0 && $sesionUsuario->idSucursalLogin < 6) {
                    $database = 'dbjosandro' . $sesionUsuario->idSucursalLogin;
                }
            }
        }
        return array(
            'factories' => array(
                'TalentoHumano\Modelo\DAO\EmpleadoDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\EmpleadoDAO($dbAdapter);
                    return $tabla;
                },
                'TalentoHumano\Modelo\DAO\ContratoLaboralDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\ContratoLaboralDAO($dbAdapter);
                    return $tabla;
                },
                'TalentoHumano\Modelo\DAO\CargoDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\CargoDAO($dbAdapter);
                    return $tabla;
                },
                'TalentoHumano\Modelo\DAO\PermisoLaboralDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\PermisoLaboralDAO($dbAdapter);
                    return $tabla;
                },
                'TalentoHumano\Modelo\DAO\UbicacionDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\UbicacionDAO($dbAdapter);
                    return $tabla;
                },
            ),
        );
    }

}
