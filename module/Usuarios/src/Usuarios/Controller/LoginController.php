<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Usuarios\Formularios\LoginForm;

class LoginController extends AbstractActionController {

    public function loginAction() {
        if ($this->identity()) {
            return $this->redirect()->toUrl('/gimnasio/inicio');
        }
        $formLogin = new LoginForm('IniciarSesion');
        $viewModel = new ViewModel(array('formLogin' => $formLogin));
        $peticion = $this->getRequest();
        if ($peticion->isPost()) {
            $formLogin->setData($peticion->getPost());
            if ($formLogin->isValid()) {
                $datos = $formLogin->getData();
                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $config = $this->getServiceLocator()->get('Config');
                $passwordSeguro = $config['passwordSeguro'];
                $authAdapter = new AuthAdapter($dbAdapter, 'v_usuario_empleado', 'login', 'password', "MD5(CONCAT('$passwordSeguro', ?, passwordseguro)) AND estado = 'Activo'");
                $authAdapter->setIdentity($datos['login'])->setCredential($datos['password']);
                $auth = new AuthenticationService();
                $result = $auth->authenticate($authAdapter);
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $this->flashMessenger()->addErrorMessage("USUARIO O CONTRASEÑA INCORRECTO");
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $this->flashMessenger()->addErrorMessage("USUARIO O CONTRASEÑA INCORRECTO");
                        break;
                    case Result::SUCCESS:
                        $storage = $auth->getStorage();
                        $infoSession = $authAdapter->getResultRowObject(null, 'password');
                        $idsSucursales = explode(';', $infoSession->idSucursal);
                        if (count($idsSucursales) > 0) {
                            $infoSession->idSucursalLogin = $idsSucursales[0];
                        } else {
                            $infoSession->idSucursalLogin = 0;
                        }
                        $storage->write($infoSession);

//                        $time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
////						if ($datos['rememberme']) $storage->getSession()->getManager()->rememberMe($time); // no way to get the session
//                        if ($datos['recordar']) {
//                            $sessionManager = new \Zend\Session\SessionManager();
//                            $sessionManager->rememberMe($time);
//                        }
                        $viewModel = $this->redirect()->toRoute('inicio/default', array(
                            'controller' => 'bandejaentrada',
                            'action' => 'index',
                        ));
                        break;
                    default:
                        $this->flashMessenger()->addErrorMessage("SE HA PRESENTADO UN INCONVENIENTE CON EL INICIO DE SU SESION");
                        break;
                }
            } else {
                $this->flashMessenger()->addErrorMessage("SE HA PRESENTADO UN INCONVENIENTE CON EL INICIO DE SU SESION");
            }
        }
        return $viewModel;
    }

    public function cerrarSesionAction() {
        $auth = new AuthenticationService();
        $auth->clearIdentity();
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();
        $this->flashMessenger()->addSuccessMessage("LA SESION HA TERMINADO");
        return $this->redirect()->toRoute('login/default', array('controller' => 'login', 'action' => 'login'));
    }

//------------------------------------------------------------------------------

    public function cambiarsucursalAction() {
        if ($sesionUsuario = $this->identity()) {
            $peticion = $this->getRequest();
            if ($peticion->isPost()) {
                $idSucursalLogin = (int) $this->params()->fromPost('idSucursalLogin', 0);
                if ($idSucursalLogin != 0) {
                    $auth = new AuthenticationService();
                    $storage = $auth->getStorage();
                    $sesionUsuario->idSucursalLogin = $idSucursalLogin;
                    $storage->write($sesionUsuario);
                    $error = 0;
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else {
            $error = 1;
        }
        return new JsonModel(array(
            'error' => $error
        ));
    }

//------------------------------------------------------------------------------

    public function getidsucursalloginAction() {
        if ($sesionUsuario = $this->identity()) {
            $error = 0;
            $idSucursalLogin = $sesionUsuario->idSucursalLogin;
        } else {
            $error = 1;
            $idSucursalLogin = 0;
        }
        return new JsonModel(array(
            'error' => $error,
            'idSucursalLogin' => $idSucursalLogin
        ));
    }

//------------------------------------------------------------------------------
}
