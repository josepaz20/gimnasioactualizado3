<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Usuarios\Formularios\RolForm;
use Usuarios\Modelo\Entidades\Rol;

class RolesController extends AbstractActionController {

    protected $rolesDAO;

//------------------------------------------------------------------------------    

    public function getRolesDAO() {
        if (!$this->rolesDAO) {
            $sm = $this->getServiceLocator();
            $this->rolesDAO = $sm->get('Usuarios\Modelo\DAO\RolesDAO');
        }
        return $this->rolesDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idRol = 0) {
        $form = new RolForm($action);
        if ($idRol != 0) {
            $rolOBJ = $this->getRolesDAO()->getRol($idRol);
            $form->bind($rolOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $roles = $this->getRolesDAO()->getRoles();
        return new ViewModel(array(
            'roles' => $roles,
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
                $rolOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $rolOBJ->setEstado('Registrado');
                $rolOBJ->setRegistradopor($nombreUsuario);
                $rolOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                if ($this->getRolesDAO()->guardar($rolOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('ROL REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('ROL NO REGISTRADO EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'roles',
                            'action' => 'index',
                ));
            } else {
//                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
                $ajax = false;
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/roles/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $idRol = (int) $this->params()->fromQuery('idRol', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idRol);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $rolOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $rolOBJ->setModificadopor($nombreUsuario);
                $rolOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getRolesDAO()->guardar($rolOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DEL ROL ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DEL ROL NO ACTUALIZADA EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'roles',
                            'action' => 'index',
                ));
            } else {
//                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
                $ajax = false;
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/roles/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function detailAction() {
        $idRol = (int) $this->params()->fromQuery('idRol', 0);
        $action = 'detail';
        $form = $this->getFormulario($action, $idRol);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/roles/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idRol = (int) $this->params()->fromPost('pk_tipo_evidencia_id', 0);
            if ($this->getRolesDAO()->eliminar($idRol) > 0) {
                $this->flashMessenger()->addSuccessMessage('ROL ELIMINADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('ROL NO ELIMINADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'roles',
                        'action' => 'index',
            ));
        }
        $idRol = (int) $this->params()->fromQuery('idRol', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idRol);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/roles/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
