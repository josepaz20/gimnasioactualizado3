<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Usuarios\Formularios\AccionForm;
use Usuarios\Modelo\Entidades\Accion;

class AccionesController extends AbstractActionController {

    protected $accionesDAO;

//------------------------------------------------------------------------------    

    public function getAccionesDAO() {
        if (!$this->accionesDAO) {
            $sm = $this->getServiceLocator();
            $this->accionesDAO = $sm->get('Usuarios\Modelo\DAO\AccionesDAO');
        }
        return $this->accionesDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idAccion = 0) {
        $form = new AccionForm($action);
        if ($idAccion != 0) {
            $accionOBJ = $this->getAccionesDAO()->getAccion($idAccion);
            $form->bind($accionOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $acciones = $this->getAccionesDAO()->getAcciones();
        return new ViewModel(array(
            'acciones' => $acciones,
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
                $accionOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $accionOBJ->setEstado('Registrado');
                $accionOBJ->setRegistradopor($nombreUsuario);
                $accionOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                if ($this->getAccionesDAO()->guardar($accionOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('ROL REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('ROL NO REGISTRADO EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'acciones',
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
        $view->setTemplate('usuarios/acciones/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $idAccion = (int) $this->params()->fromQuery('idAccion', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idAccion);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $accionOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $accionOBJ->setModificadopor($nombreUsuario);
                $accionOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getAccionesDAO()->guardar($accionOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DEL ROL ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DEL ROL NO ACTUALIZADA EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'acciones',
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
        $view->setTemplate('usuarios/acciones/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function detailAction() {
        $idAccion = (int) $this->params()->fromQuery('idAccion', 0);
        $action = 'detail';
        $form = $this->getFormulario($action, $idAccion);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/acciones/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idAccion = (int) $this->params()->fromPost('pk_tipo_evidencia_id', 0);
            if ($this->getAccionesDAO()->eliminar($idAccion) > 0) {
                $this->flashMessenger()->addSuccessMessage('ROL ELIMINADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('ROL NO ELIMINADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'acciones',
                        'action' => 'index',
            ));
        }
        $idAccion = (int) $this->params()->fromQuery('idAccion', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idAccion);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/acciones/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function actualizarAccionesAction() {
        $listaAcciones = array();
        $manager = $this->getServiceLocator()->get('ModuleManager');
        $modules = $manager->getLoadedModules();
        $loadedModules = array_keys($modules);
        $skipActionsList = array('notFoundAction', 'getMethodFromAction');
        foreach ($loadedModules as $loadedModule) {
            if ($loadedModule != 'EdpModuleLayouts') {
                $moduleClass = '\\' . $loadedModule . '\Module';
                $moduleObject = new $moduleClass;
                $config = $moduleObject->getConfig();
                if (array_key_exists('controllers', $config)) {
                    $controllers = array_keys($config['controllers']['invokables']);
                    foreach ($controllers as $controller) {
                        $tmpArray = get_class_methods($controller . 'Controller');
                        if (is_array($tmpArray)) {
                            foreach ($tmpArray as $action) {
                                if (substr($action, strlen($action) - 6) === 'Action' && !in_array($action, $skipActionsList)) {
                                    $action = substr($action, 0, -6);
                                    if (!in_array($action, $listaAcciones)) {
                                        $listaAcciones[] = $action;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $listaAccionesBD = $this->getAccionesDAO()->getAccionesArray();
        $listaActulizar = array_diff($listaAcciones, $listaAccionesBD);
//        print_r($listaAcciones);
//        echo '<br><br>';
//        print_r($listaAccionesBD);
//        echo '<br><br>';
//        print_r($listaActulizar);
        $nombreUsuario = '';
        if ($sesionUsuario = $this->identity()) {
            $nombreUsuario = $sesionUsuario->nombresapellidos;
        }
        foreach ($listaActulizar as $accion) {
            $accionOBJ = new Accion(array(
                'pk_accion_id' => 0,
                'accion' => $accion,
                'descripcion' => '',
                'estado' => 'Registrado',
                'registradopor' => $nombreUsuario,
                'fechahorareg' => date('Y-m-d H:i:s'),
                'modificadopor' => '',
                'fechahoramod' => '0000-00-00 00:00:00'
            ));
            $this->getAccionesDAO()->guardar($accionOBJ);
        }
        $this->flashMessenger()->addSuccessMessage('ACCIONES ACTUALIZADAS EN JOSANDRO');
        return $this->redirect()->toRoute('usuarios/default', array(
                    'controller' => 'acciones',
                    'action' => 'index',
        ));
    }

    public function getAccionesSelectAction() {
        $recurso = $this->params()->fromQuery('recurso', '');
        if ($recurso == '') {
            return '';
        }
        $listaAcciones = array();
        $skipActionsList = array('notFoundAction', 'getMethodFromAction');
        $tmpArray = get_class_methods($recurso . 'Controller');
        if (is_array($tmpArray)) {
            foreach ($tmpArray as $action) {
                if (substr($action, strlen($action) - 6) === 'Action' && !in_array($action, $skipActionsList)) {
                    $action = substr($action, 0, -6);
                    if (!in_array($action, $listaAcciones)) {
                        $listaAcciones[] = $action;
                    }
                }
            }
        }
        $view = new ViewModel(array(
            'acciones' => $listaAcciones
        ));
        $view->setTerminal(true);
        return $view;
    }

}
