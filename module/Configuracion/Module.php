<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Configuracion;

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
                'Configuracion\Modelo\DAO\UbicacionDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\UbicacionDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\SucursalDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\SucursalDAO($dbAdapter);
                    return $tabla;
                },
//------------------------------------------------------------------------------                        
                'Configuracion\Modelo\DAO\TipoIdentificacionDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\TipoIdentificacionDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\TipoServicioDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\TipoServicioDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\TipoTarifaDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\TipoTarifaDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\DepartamentoDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\DepartamentoDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\MunicipioDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\MunicipioDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\TipoViviendaDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\TipoViviendaDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\ZonaDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\ZonaDAO($dbAdapter);
                    return $tabla;
                },
                'Configuracion\Modelo\DAO\BarrioDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\BarrioDAO($dbAdapter);
                    return $tabla;
                },
            ),
        );
    }

}
