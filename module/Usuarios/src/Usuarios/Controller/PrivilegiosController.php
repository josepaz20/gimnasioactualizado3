<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Usuarios\Formularios\PrivilegioForm;
use Usuarios\Modelo\Entidades\Privilegio;

class PrivilegiosController extends AbstractActionController {

    protected $privilegiosDAO;
    protected $recursosDAO;
    protected $accionesDAO;
    protected $rolesDAO;

//------------------------------------------------------------------------------    

    public function getPrivilegiosDAO() {
        if (!$this->privilegiosDAO) {
            $sm = $this->getServiceLocator();
            $this->privilegiosDAO = $sm->get('Usuarios\Modelo\DAO\PrivilegiosDAO');
        }
        return $this->privilegiosDAO;
    }

    public function getRecursosDAO() {
        if (!$this->recursosDAO) {
            $sm = $this->getServiceLocator();
            $this->recursosDAO = $sm->get('Usuarios\Modelo\DAO\RecursosDAO');
        }
        return $this->recursosDAO;
    }

    public function getAccionesDAO() {
        if (!$this->accionesDAO) {
            $sm = $this->getServiceLocator();
            $this->accionesDAO = $sm->get('Usuarios\Modelo\DAO\AccionesDAO');
        }
        return $this->accionesDAO;
    }

    public function getRolesDAO() {
        if (!$this->rolesDAO) {
            $sm = $this->getServiceLocator();
            $this->rolesDAO = $sm->get('Usuarios\Modelo\DAO\RolesDAO');
        }
        return $this->rolesDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idPrivilegio = 0) {
        $recursos = $this->getRecursosDAO()->getRecursos();
        $listaRecursos = array();
        foreach ($recursos as $recursoOBJ) {
            $listaRecursos[$recursoOBJ->getPk_recursoacl_id()] = $recursoOBJ->getRecursoacl();
        }
        $roles = $this->getRolesDAO()->getRoles();
        foreach ($roles as $rolOBJ) {
            $listaRoles[$rolOBJ->getPk_rol_id()] = $rolOBJ->getRol();
        }
        $form = new PrivilegioForm($action, $listaRecursos, $listaRoles);
        if ($idPrivilegio != 0) {
            $privilegioOBJ = $this->getPrivilegiosDAO()->getPrivilegio($idPrivilegio);
            $recursoOBJ = $this->getRecursosDAO()->getRecurso($privilegioOBJ->getFk_recursoacl_id());
            $accionOBJ = $this->getAccionesDAO()->getAccion($privilegioOBJ->getFk_accion_id());

            $listaAcciones = array();
            $skipActionsList = array('notFoundAction', 'getMethodFromAction');
            $tmpArray = get_class_methods($recursoOBJ->getRecursoacl() . 'Controller');
            if (is_array($tmpArray)) {
                foreach ($tmpArray as $action) {
                    if (substr($action, strlen($action) - 6) === 'Action' && !in_array($action, $skipActionsList)) {
                        $action = substr($action, 0, -6);
                        if (!in_array($action, $listaAcciones)) {
                            $listaAcciones[$action] = $action;
                        }
                    }
                }
            }
            $form->get('fk_accion_id')->setValueOptions($listaAcciones);
//            $privilegioOBJ->set
            $form->bind($privilegioOBJ);
            $form->get('fk_accion_id')->setValue($accionOBJ->getAccion());
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $idRol = $this->params()->fromQuery('idRolBusq', 0);
        return new ViewModel(array(
            'privilegios' => $this->getPrivilegiosDAO()->getPrivilegios(array('fk_rol_id' => $idRol)),
            'roles' => $this->getRolesDAO()->getRoles(),
            'idRol' => $idRol,
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
                $privilegioOBJ = new Privilegio($form->getData());
                $accion = $this->params()->fromPost('fk_accion_id', '');
                $accionOBJ = $this->getAccionesDAO()->getAccionByNombre($accion);
                $privilegioOBJ->setFk_accion_id($accionOBJ->getPk_accion_id());

//                $rol = $this->params()->fromPost('fk_rol_id', '');
//                $rolOBJ = $this->getRolesDAO()->getRolByNombre($rol);
//                $privilegioOBJ->setFk_rol_id($rolOBJ->getPk_rol_id());
                if ($this->getPrivilegiosDAO()->existePrivilegio($privilegioOBJ->getFk_recursoacl_id(), $privilegioOBJ->getFk_accion_id(), $privilegioOBJ->getFk_rol_id()) == 1) {
                    $this->flashMessenger()->addErrorMessage('EL PRIVILEGIO YA ESTA REGISTRADO EN JOSANDRO');
                    return $this->redirect()->toRoute('usuarios/default', array(
                                'controller' => 'privilegios',
                                'action' => 'index',
                    ));
                }
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $privilegioOBJ->setEstado('Registrado');
                $privilegioOBJ->setRegistradopor($nombreUsuario);
                $privilegioOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                if ($this->getPrivilegiosDAO()->guardar($privilegioOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('PRIVILEGIO REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('PRIVILEGIO NO REGISTRADO EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'privilegios',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'privilegios',
                            'action' => 'index',
                ));
//                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
//                $ajax = false;
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/privilegios/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $idPrivilegio = (int) $this->params()->fromQuery('idPrivilegio', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idPrivilegio);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $privilegioOBJ = new Privilegio($form->getData());

                $idRecursoOLD = $this->params()->fromPost('fk_recursoacl_id_old', '');
                $accionOLD = $this->params()->fromPost('fk_accion_id_old', '');
                $idRolOLD = $this->params()->fromPost('fk_rol_id_old', '');

                $accion = $this->params()->fromPost('fk_accion_id', '');
                $accionOBJ = $this->getAccionesDAO()->getAccionByNombre($accion);
                $privilegioOBJ->setFk_accion_id($accionOBJ->getPk_accion_id());

                if ($idRecursoOLD != $privilegioOBJ->getFk_recursoacl_id() || $accionOLD != $accion || $idRolOLD != $privilegioOBJ->getFk_rol_id()) {
                    if ($this->getPrivilegiosDAO()->existePrivilegio($privilegioOBJ->getFk_recursoacl_id(), $privilegioOBJ->getFk_accion_id(), $privilegioOBJ->getFk_rol_id()) == 1) {
                        $this->flashMessenger()->addErrorMessage('EL PRIVILEGIO YA ESTA REGISTRADO EN JOSANDRO');
                        return $this->redirect()->toRoute('usuarios/default', array(
                                    'controller' => 'privilegios',
                                    'action' => 'index',
                        ));
                    }
                }

                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $privilegioOBJ->setModificadopor($nombreUsuario);
                $privilegioOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getPrivilegiosDAO()->guardar($privilegioOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DEL PRIVILEGIO ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DEL PRIVILEGIO NO ACTUALIZADA EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'privilegios',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'privilegios',
                            'action' => 'index',
                ));
//                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
//                $ajax = false;
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/privilegios/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function detailAction() {
        $idPrivilegio = (int) $this->params()->fromQuery('idPrivilegio', 0);
        $action = 'detail';
        $form = $this->getFormulario($action, $idPrivilegio);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/privilegios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idPrivilegio = (int) $this->params()->fromPost('pk_privilegio_id', 0);
            if ($this->getPrivilegiosDAO()->eliminar($idPrivilegio) > 0) {
                $this->flashMessenger()->addSuccessMessage('PRIVILEGIO ELIMINADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('PRIVILEGIO NO ELIMINADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'privilegios',
                        'action' => 'index',
            ));
        }
        $idPrivilegio = (int) $this->params()->fromQuery('idPrivilegio', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idPrivilegio);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/privilegios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function existePrivilegioAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $existe = 1;
        if ($request->isGet()) {
            $idRecurso = $this->params()->fromQuery('idRecurso', 0);
            $accion = $this->params()->fromQuery('accion', '');
            $idRol = $this->params()->fromQuery('idRol', '');
            $accionOBJ = $this->getAccionesDAO()->getAccionByNombre($accion);
            $existe = $this->getPrivilegiosDAO()->existePrivilegio($idRecurso, $accionOBJ->getPk_accion_id(), $idRol);
        }
        $response->setContent(Json::encode(array('existe' => $existe)));
        return $response;
    }

}
