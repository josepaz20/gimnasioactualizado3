<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Layout;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->iniLayout($e);
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

    protected function iniLayout($e) {
        $autenticarOBJ = new AuthenticationService();
        $idRol = 0;
        if ($autenticarOBJ->hasIdentity()) {
            $usuario = $autenticarOBJ->getIdentity();
            $idRol = $usuario->fk_rol_id;
        }
        $layout = $e->getViewModel();
        $header = new ViewModel();
        $header->setTemplate('layout/header');
        $layout->addChild($header, 'header');
        $footer = new ViewModel();
        $footer->setTemplate('layout/footer');
        $layout->addChild($footer, 'footer');
        $headerlogin = new ViewModel();
        $headerlogin->setTemplate('layout/headerlogin');
        $layout->addChild($headerlogin, 'headerlogin');
        $menu = new ViewModel();
        switch ($idRol) {
            case 1:
                $menu->setTemplate('layout/menu');
                break;
            case 13:
                $menu->setTemplate('layout/menuasignacionequipos');
                break;
            default:
                $menu->setTemplate('layout/menu');
                break;
        }
//        $menu->setTemplate('layout/menu');
        $layout->addChild($menu, 'menu');
        $global = new ViewModel();
        $global->setTemplate('layout/global');
        $layout->addChild($global, 'global');
    }

}
