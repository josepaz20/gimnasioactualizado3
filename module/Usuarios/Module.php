<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuarios;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class Module {

//******************************************************************************

    protected $usuarioDAO;
    protected $rolesDAO;
    protected $recursosDAO;
    protected $privilegiosDAO;

//------------------------------------------------------------------------------    


    public function setDAOs(MvcEvent $e) {
        if (!$this->usuarioDAO) {
            $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
            $tabla = new Modelo\DAO\UsuarioDAO($dbAdapter);
            $this->usuarioDAO = $tabla;
        }
        if (!$this->rolesDAO) {
            $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
            $tabla = new Modelo\DAO\RolesDAO($dbAdapter);
            $this->rolesDAO = $tabla;
        }
        if (!$this->recursosDAO) {
            $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
            $tabla = new Modelo\DAO\RecursosDAO($dbAdapter);
            $this->recursosDAO = $tabla;
        }
        if (!$this->privilegiosDAO) {
            $dbAdapter = $e->getApplication()->getServiceManager()->get('Zend\Db\Adapter\Adapter');
            $tabla = new Modelo\DAO\PrivilegiosDAO($dbAdapter);
            $this->privilegiosDAO = $tabla;
        }
    }

//------------------------------------------------------------------------------    

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
//        $translator = new Translator();
//        $translator->addTranslationFile('phpArray', 'vendor\zendframework\zend-i18n-resources\languages\es\Zend_Validate.php');
//        $translator->addTranslationFile('phpArray', 'resources/languages/en/Zend_Validate.php', 'default', 'es_ES');
//        AbstractValidator::setDefaultTranslator(new \Zend\Mvc\I18n\Translator($translator));
        $this->bootstrapSession($e);
        $this->cargarACLs($e);
//        $e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'validarSession'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkAcl'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'verificarError'));
    }

//------------------------------------------------------------------------------    

    public function cargarACLs(MvcEvent $e) {
        $acl = new Acl();
        $this->setDAOs($e);
        $roles = $this->rolesDAO->getRolesInitACL();
        foreach ($roles as $rol) {
            if (!$acl->hasRole($rol['rol'])) {
                $padresRol = array();
                $padres = $this->rolesDAO->getPadresRolInitACL(array('fk_rol_id' => $rol['pk_rol_id']));
                foreach ($padres as $rolPadre) {
                    $padresRol[] = $rolPadre['rolPadre'];
                }
                $acl->addRole(new GenericRole($rol['rol'], $padresRol));
            }
        }

        $recursos = $this->recursosDAO->getRecursosInitACL();
        foreach ($recursos as $recurso) {
            if (!$acl->hasResource($recurso['recursoacl'])) {
                $acl->addResource(new GenericResource($recurso['recursoacl']));
            }
        }
        $acl->addResource(new GenericResource('inicio'));

        $privilegiosOK = $this->privilegiosDAO->getPrivilegiosInitACL(array('permiso' => 'OK'));
        foreach ($privilegiosOK as $privilegio) {
            $acl->allow($privilegio['rol'], $privilegio['recursoacl'], $privilegio['accion']);
        }
        $acl->allow('Invitado', 'Usuarios\Controller\Login', null);
        $acl->allow('Administrador', null, null);

        $privilegiosNO = $this->privilegiosDAO->getPrivilegiosInitACL(array('permiso' => 'NO'));
        foreach ($privilegiosNO as $privilegio) {
            $acl->deny($privilegio['rol'], $privilegio['recursoacl'], $privilegio['accion']);
        }
        $e->getViewModel()->acl = $acl;
    }

    public function checkACL(MvcEvent $e) {
        $routeMatch = $e->getRouteMatch();
        $controlador = $routeMatch->getParam('controller');
        if (!$e->getViewModel()->acl->hasResource($controlador)) {
            echo 'RECURSO ' . $controlador . ' NO DEFINIDO';
            $response = $e->getResponse();
            $response->setStatusCode(404);
            return;
        }
        $accion = $routeMatch->getParam('action');
        $rol = 'Invitado';
        $autenticarOBJ = new AuthenticationService();
        if ($autenticarOBJ->hasIdentity()) {
            $usuario = $autenticarOBJ->getIdentity();
            $rolOBJ = $this->rolesDAO->getRol($usuario->fk_rol_id);
            $rol = $rolOBJ->getRol();
        }
        if (!$e->getViewModel()->acl->isAllowed($rol, $controlador, $accion)) {
            $response = $e->getResponse();
            $response->setStatusCode(404);
            return;
        }
    }

//------------------------------------------------------------------------------

    public function bootstrapSession($e) {
        $session = $e->getApplication()->getServiceManager()->get('Zend\Session\SessionManager');
        $session->start();
        $container = new Container('initialized');
        if (!isset($container->init)) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request = $serviceManager->get('Request');
            $session->regenerateId(true);
            $container->init = 1;
            $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
            $config = $serviceManager->get('Config');
            if (!isset($config['session'])) {
                return;
            }
            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();
                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }
                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }

//------------------------------------------------------------------------------

    public function validarSession(MvcEvent $e) {
        $controlador = $e->getRouteMatch()->getParam('controller');
        $auth = new AuthenticationService();
        $identi = $auth->getStorage()->read();
        if (($identi == false || $identi == null) && $controlador != 'Usuarios\Controller\Login') {
//            echo '<br><br> F U E R A <br><br>';
            $url = $e->getRouter()->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->sendHeaders();
            exit;
        }
    }

//------------------------------------------------------------------------------

    function verificarError(MvcEvent $e) {
        $response = $e->getResponse();
//        echo $response->getStatusCode();
        switch ($response->getStatusCode()) {
            case 403:
                $model = new ViewModel();
                $model->setTerminal(true);
                $model->setTemplate('error/403');
                $e->setResult($model);
                break;
            case 404:
                $model = new ViewModel();
                $model->setTerminal(true);
                $model->setTemplate('error/404');
                $e->setResult($model);
                break;
        }
    }

//------------------------------------------------------------------------------

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
                    $database = 'dbjosandro_' . $sesionUsuario->idSucursalLogin;
                }
            }
        }
        return array(
            'factories' => array(
                'Usuarios\Modelo\DAO\RolesDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\RolesDAO($dbAdapter);
                    return $tabla;
                },
                'Usuarios\Modelo\DAO\RecursosDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\RecursosDAO($dbAdapter);
                    return $tabla;
                },
                'Usuarios\Modelo\DAO\AccionesDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\AccionesDAO($dbAdapter);
                    return $tabla;
                },
                'Usuarios\Modelo\DAO\PrivilegiosDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\PrivilegiosDAO($dbAdapter);
                    return $tabla;
                },
                'Usuarios\Modelo\DAO\UsuarioDAO' => function($sm) {
                    global $database;
                    if (is_null($database)) {
                        header("location: /gimnasio/login/login/cerrarSesion");
                        exit();
                    }
                    $dbAdapter = $sm->get($database);
                    $tabla = new Modelo\DAO\UsuarioDAO($dbAdapter);
                    return $tabla;
                },
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];
                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }
                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }
                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            // class should be fetched from service manager since it will require constructor arguments
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                        }
                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                }
            )
        );
    }

}
