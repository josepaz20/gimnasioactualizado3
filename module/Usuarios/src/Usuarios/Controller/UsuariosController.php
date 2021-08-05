<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Usuarios\Formularios\UsuarioForm;
use Usuarios\Formularios\CambiocontrasenaForm;
use Usuarios\Modelo\Entidades\Usuario;

class UsuariosController extends AbstractActionController {

    protected $usuarioDAO;
    protected $empleadoDAO;
    protected $rolesDAO;
    protected $sucursalDAO;

//------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'idUsuario' => $sesionUsuario->pk_usuario_id,
                'idSucursal' => $sesionUsuario->idSucursal,
                'idEmpleado' => $sesionUsuario->fk_empleado_id,
                'idRol' => $sesionUsuario->fk_rol_id,
                'rol' => $sesionUsuario->rol,
                'nombresapellidos' => substr(trim($sesionUsuario->nombresapellidos), 0, 20),
            );
        } else {
            $infoSession = array(
                'idUsuario' => 0,
                'idSucursal' => '',
                'idEmpleado' => 0,
                'idRol' => 0,
                'rol' => '',
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

//------------------------------------------------------------------------------    

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Usuarios\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    public function getEmpleadoDAO() {
        if (!$this->empleadoDAO) {
            $sm = $this->getServiceLocator();
            $this->empleadoDAO = $sm->get('TalentoHumano\Modelo\DAO\EmpleadoDAO');
        }
        return $this->empleadoDAO;
    }

    public function getRolesDAO() {
        if (!$this->rolesDAO) {
            $sm = $this->getServiceLocator();
            $this->rolesDAO = $sm->get('Usuarios\Modelo\DAO\RolesDAO');
        }
        return $this->rolesDAO;
    }

    public function getSucursalDAO() {
        if (!$this->sucursalDAO) {
            $sm = $this->getServiceLocator();
            $this->sucursalDAO = $sm->get('Sucursales\Modelo\DAO\SucursalDAO');
        }
        return $this->sucursalDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idUsuario = 0) {
        $listaEmpleados = array();
        $listaRoles = array();
        $empleados = $this->getEmpleadoDAO()->getEmpleadosUsuarios("empleado.estado = 'Activo'");
        foreach ($empleados as $empleado) {
            $nombres = $empleado->getNombres();
            $apellidos = $empleado->getApellidos();
            $listaEmpleados[$empleado->getIdEmpleado()] = trim($nombres) . ' ' . trim($apellidos);
        }
        $roles = $this->getRolesDAO()->getRoles();
        foreach ($roles as $rolOBJ) {
            $listaRoles[$rolOBJ->getPk_rol_id()] = $rolOBJ->getRol();
        }
        $form = new UsuarioForm($action, $listaEmpleados, $listaRoles);
        if ($idUsuario != 0) {
            $usuarioOBJ = $this->getUsuarioDAO()->getUsuario($idUsuario);
            $form->bind($usuarioOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $idSucursal = (int) $this->params()->fromQuery('idSucursalBusq', 0);
        if ($idSucursal == 0) {
            $session = $this->getInfoSesionUsuario();
            $idsSucursales = explode(';', $session['idSucursal']);
            if (count($idsSucursales) > 0) {
                $idSucursal = (int) $idsSucursales[0];
            }
        }
        $idSucursal = -1;
        return new ViewModel(array(
            'usuarios' => $this->getUsuarioDAO()->getUsuarios(array('idSucursal' => $idSucursal)),
            'sucursales' => $this->getSucursalDAO()->getSucursalesSelect(),
            'idSucursal' => $idSucursal,
        ));
    }

    public function addAction() {
        $action = 'add';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $idSucursalAsignada = (int) $this->params()->fromPost('idSucursalAsignada', 3);
                $usuarioOBJ = new Usuario($form->getData());
                $config = $this->getServiceLocator()->get('Config');
                $passwordApp = $config['passwordSeguro'];
                $passwordSeguro = rand();
                $password = md5($passwordApp . $usuarioOBJ->getPassword() . $passwordSeguro);
                $usuarioOBJ->setPassword($password);
                $usuarioOBJ->setPasswordseguro($passwordSeguro);
                $usuarioOBJ->setEstado('Activo');
                $session = $this->getInfoSesionUsuario();
                $usuarioOBJ->setRegistradopor($session['nombresapellidos']);
                $usuarioOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                $usuarioOBJ->setFechahoramod('0000-00-00 00:00:00');
                if ($this->getUsuarioDAO()->guardar($usuarioOBJ, $idSucursalAsignada) > 0) {
                    $this->flashMessenger()->addSuccessMessage('USUARIO REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('USUARIO NO REGISTRADO EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'usuarios',
                        'action' => 'index',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idUsuario);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuarioOBJ = new Usuario($form->getData());
                $session = $this->getInfoSesionUsuario();
                $usuarioOBJ->setModificadopor($session['nombresapellidos']);
                $usuarioOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getUsuarioDAO()->actualizar($usuarioOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DEL USUARIO ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DEL USUARIO NO ACTUALIZADA EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'usuarios',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'usuarios',
                        'action' => 'index',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formularioEdit');
        $view->setTerminal($ajax);
        return $view;
    }

    public function detailAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'detail';
        $form = $this->getFormulario($action, $idUsuario);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idUsuario = (int) $this->params()->fromPost('pk_usuario_id', 0);
            $session = $this->getInfoSesionUsuario();
            if ($this->getUsuarioDAO()->eliminar($idUsuario, $session['nombresapellidos']) > 0) {
                $this->flashMessenger()->addSuccessMessage('USUARIO ELIMINADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('USUARIO NO ELIMINADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'usuarios',
                        'action' => 'index',
            ));
        }
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idUsuario);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function activarAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idUsuario = (int) $this->params()->fromPost('pk_usuario_id', 0);
            $session = $this->getInfoSesionUsuario();
            if ($this->getUsuarioDAO()->activar($idUsuario, $session['nombresapellidos']) > 0) {
                $this->flashMessenger()->addSuccessMessage('USUARIO ACTIVADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('USUARIO NO ACTIVADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'usuarios',
                        'action' => 'index',
            ));
        }
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'activar';
        $form = $this->getFormulario($action, $idUsuario);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function bloquearAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idUsuario = (int) $this->params()->fromPost('pk_usuario_id', 0);
            $session = $this->getInfoSesionUsuario();
            if ($this->getUsuarioDAO()->bloquear($idUsuario, $session['nombresapellidos']) > 0) {
                $this->flashMessenger()->addSuccessMessage('USUARIO BLOQUEADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('USUARIO NO BLOQUEADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'usuarios',
                        'action' => 'index',
            ));
        }
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'bloquear';
        $form = $this->getFormulario($action, $idUsuario);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function getLoginAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $error = 1;
        $login = '';
        $nombresapellidos = '';
        $sexo = 'Masculino';
        if ($request->isGet()) {
            $cont = 0;
            $idEmpleado = $this->params()->fromQuery('idEmpleado', 0);
            $empleadoOBJ = $this->getEmpleadoDAO()->getEmpleado($idEmpleado);
            $sexo = $empleadoOBJ->getSexo();
            $nombres = $empleadoOBJ->getNombres();
            $apellidos = $empleadoOBJ->getApellidos();
            $nombresapellidos = trim($nombres) . ' ' . trim($apellidos);
            $partesApellidos = explode(' ', $apellidos);
            $primerApellido = $partesApellidos[0];
            $login = strtolower($nombres[0] . $primerApellido);
            while ($this->getUsuarioDAO()->existeLogin($login) && $cont < 100) {
                $login = $login . rand(1, 1000);
                $cont++;
            }
            if ($cont <= 100) {
                $error = 0;
            }
        }
        $response->setContent(Json::encode(array(
                    'error' => $error,
                    'login' => $login,
                    'nombresapellidos' => $nombresapellidos,
                    'sexo' => $sexo,
        )));
        return $response;
    }

    public function cambiarcontrasenaAction() {
        $form = new CambiocontrasenaForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = 1;
            $response = $this->getResponse();
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $usuarioOBJ = $this->getUsuarioDAO()->getUsuario($session['idUsuario']);
                $password = $this->params()->fromPost('password', '');
                $passwordactual = $this->params()->fromPost('passwordactual', '');
                $config = $this->getServiceLocator()->get('Config');
                $passwordApp = $config['passwordSeguro'];
                if ($usuarioOBJ->getPassword() == md5($passwordApp . $passwordactual . $usuarioOBJ->getPasswordseguro())) {
                    $passwordSeguro = rand();
                    $newpassword = md5($passwordApp . $password . $passwordSeguro);
                    if ($this->getUsuarioDAO()->cambiarcontrasena($session['idUsuario'], $newpassword, $passwordSeguro, $session['nombresapellidos']) > 0) {
                        $error = 0;
                    }
                } else {
                    $error = 2;
                }
            }
            $response->setContent(Json::encode(array(
                        'error' => $error,
                        'actual' => $usuarioOBJ->getPassword(),
                        'digitado' => md5($passwordApp . $passwordactual . $usuarioOBJ->getPasswordseguro()),
            )));
            return $response;
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/usuarios/cambiarcontrasena');
        $view->setTerminal(true);
        return $view;
    }

    public function gestionsucursalesAction() {
        $idUsuario = $this->params()->fromQuery('idUsuario', 0);
        $view = new ViewModel(array(
            'idUsuario' => $idUsuario,
            'sucursales' => $this->getUsuarioDAO()->getSucursales(),
            'sucursalesAsignadas' => $this->getUsuarioDAO()->getSucursalesAsignadas($idUsuario),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function asignarsucursalesAction() {
        $idUsuario = $this->params()->fromPost('idUsuario', 0);
        $asignadas = $this->params()->fromPost('asignadas', array());
        $sucursalesUsuario = $this->getUsuarioDAO()->getSucursalesAsignadas($idUsuario);
        $agregar = array();
        $eliminar = array();
        foreach ($asignadas as $asignada) {
            if (!in_array($asignada, $sucursalesUsuario)) {
                $agregar[] = $asignada;
            }
        }
        foreach ($sucursalesUsuario as $sucursalUsuario) {
            if (!in_array($sucursalUsuario, $asignadas)) {
                $eliminar[] = $sucursalUsuario;
            }
        }
        $session = $this->getInfoSesionUsuario();
        if ($this->getUsuarioDAO()->gestionSucursalesUsuario($eliminar, $agregar, $idUsuario, $session['nombresapellidos'])) {
            $this->flashMessenger()->addSuccessMessage('SUCURSALES DE USUARIO GUARDADAS EN JOSANDRO');
        } else {
            $this->flashMessenger()->addErrorMessage('SUCURSALES DE USUARIO NO GUARDADAS EN JOSANDRO');
        }
        return $this->redirect()->toRoute('usuarios/default', array(
                    'controller' => 'usuarios',
                    'action' => 'index',
        ));
    }

}
