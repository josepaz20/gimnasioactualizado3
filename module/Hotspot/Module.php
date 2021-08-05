<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hotspot;

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
        return array(
            'factories' => array(
                'Hotspot\Modelo\DAO\RegistroDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbjosandro_');
                    $tabla = new Modelo\DAO\RegistroDAO($dbAdapter);
                    return $tabla;
                },
                'Hotspot\Modelo\DAO\PreguntaDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbhotspot');
                    $tabla = new Modelo\DAO\PreguntaDAO($dbAdapter);
                    return $tabla;
                },
                'Hotspot\Modelo\DAO\RespuestaDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbhotspot');
                    $tabla = new Modelo\DAO\RespuestaDAO($dbAdapter);
                    return $tabla;
                },
                'Hotspot\Modelo\DAO\ProductoDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbjosandro_');
                    $tabla = new Modelo\DAO\ProductoDAO($dbAdapter);
                    return $tabla;
                },
            ),
        );
    }

}
