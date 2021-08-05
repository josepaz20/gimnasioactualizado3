<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Finanzas;

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
                   'finanzas\Modelo\DAO\FinanzasDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbjosandro_');
                    $tabla = new Modelo\DAO\FinanzasDAO($dbAdapter);
                    return $tabla;
                },
                   'finanzas\Modelo\DAO\ProductosDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbjosandro_');
                    $tabla = new Modelo\DAO\ProductosDAO($dbAdapter);
                    return $tabla;
                },
                   'finanzas\Modelo\DAO\AsistenciaDAO' => function($sm) {
                    $dbAdapter = $sm->get('dbjosandro_');
                    $tabla = new Modelo\DAO\AsistenciaDAO($dbAdapter);
                    return $tabla;
                },
                
                
            ),
        );
    }

}
