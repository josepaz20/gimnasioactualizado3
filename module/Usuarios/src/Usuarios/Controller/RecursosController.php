<?php

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Usuarios\Formularios\RecursoForm;
use Usuarios\Modelo\Entidades\Recurso;

class RecursosController extends AbstractActionController {

    protected $recursosDAO;

//------------------------------------------------------------------------------    

    public function getRecursosDAO() {
        if (!$this->recursosDAO) {
            $sm = $this->getServiceLocator();
            $this->recursosDAO = $sm->get('Usuarios\Modelo\DAO\RecursosDAO');
        }
        return $this->recursosDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idRecurso = 0) {
        $form = new RecursoForm($action);
        if ($idRecurso != 0) {
            $recursoOBJ = $this->getRecursosDAO()->getRecurso($idRecurso);
            $form->bind($recursoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $recursos = $this->getRecursosDAO()->getRecursos();
        return new ViewModel(array(
            'recursos' => $recursos,
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
                $recursoOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $recursoOBJ->setEstado('Registrado');
                $recursoOBJ->setRegistradopor($nombreUsuario);
                $recursoOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                if ($this->getRecursosDAO()->guardar($recursoOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('ROL REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('ROL NO REGISTRADO EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'recursos',
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
        $view->setTemplate('usuarios/recursos/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $idRecurso = (int) $this->params()->fromQuery('idRecurso', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idRecurso);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $recursoOBJ = new Rol($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $recursoOBJ->setModificadopor($nombreUsuario);
                $recursoOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getRecursosDAO()->guardar($recursoOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DEL ROL ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DEL ROL NO ACTUALIZADA EN JOSANDRO');
                }
                return $this->redirect()->toRoute('usuarios/default', array(
                            'controller' => 'recursos',
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
        $view->setTemplate('usuarios/recursos/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function detailAction() {
        $idRecurso = (int) $this->params()->fromQuery('idRecurso', 0);
        $action = 'detail';
        $form = $this->getFormulario($action, $idRecurso);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/recursos/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idRecurso = (int) $this->params()->fromPost('pk_tipo_evidencia_id', 0);
            if ($this->getRecursosDAO()->eliminar($idRecurso) > 0) {
                $this->flashMessenger()->addSuccessMessage('ROL ELIMINADO DE JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('ROL NO ELIMINADO DE JOSANDRO');
            }
            return $this->redirect()->toRoute('usuarios/default', array(
                        'controller' => 'recursos',
                        'action' => 'index',
            ));
        }
        $idRecurso = (int) $this->params()->fromQuery('idRecurso', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idRecurso);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('usuarios/recursos/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function actualizarRecursosAction() {
        $listaRecursos = array();
        $manager = $this->getServiceLocator()->get('ModuleManager');
        $modules = $manager->getLoadedModules();
        $loadedModules = array_keys($modules);
        foreach ($loadedModules as $loadedModule) {
            if ($loadedModule != 'EdpModuleLayouts') {
                $moduleClass = '\\' . $loadedModule . '\Module';
                $moduleObject = new $moduleClass;
                $config = $moduleObject->getConfig();
                if (array_key_exists('controllers', $config)) {
                    $controllers = array_keys($config['controllers']['invokables']);
                    foreach ($controllers as $controller) {
                        array_push($listaRecursos, $controller);
                    }
                }
            }
        }
        $listaRecursosBD = $this->getRecursosDAO()->getRecursosArray();
        $listaActulizar = array_diff($listaRecursos, $listaRecursosBD);
//        print_r($listaRecursos);
//        echo '<br><br>';
//        print_r($listaRecursosBD);
//        echo '<br><br>';
//        print_r($listaActulizar);
        $nombreUsuario = '';
        if ($sesionUsuario = $this->identity()) {
            $nombreUsuario = $sesionUsuario->nombresapellidos;
        }
        foreach ($listaActulizar as $recursoacl) {
            $recursoOBJ = new Recurso(array(
                'pk_recursoacl_id' => 0,
                'recursoacl' => $recursoacl,
                'descripcion' => '',
                'estado' => 'Registrado',
                'registradopor' => $nombreUsuario,
                'fechahorareg' => date('Y-m-d H:i:s'),
                'modificadopor' => '',
                'fechahoramod' => '0000-00-00 00:00:00'
            ));
            $this->getRecursosDAO()->guardar($recursoOBJ);
        }
        $this->flashMessenger()->addSuccessMessage('RECURSOS ACTUALIZADOS EN JOSANDRO');
        return $this->redirect()->toRoute('usuarios/default', array(
                    'controller' => 'recursos',
                    'action' => 'index',
        ));
    }

}
