<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TalentoHumano\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use TalentoHumano\Formularios\UbicacionForm;
use TalentoHumano\Modelo\Entidades\Ubicacion;

class UbicacionController extends AbstractActionController {

    private $ubicacionDAO;

    public function getUbicacionDAO() {
        if (!$this->ubicacionDAO) {
            $sm = $this->getServiceLocator();
            $this->ubicacionDAO = $sm->get('TalentoHumano\Modelo\DAO\UbicacionDAO');
        }
        return $this->ubicacionDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idUbicacion = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new UbicacionForm($action, $onsubmit, $required);
        if ($idUbicacion != 0) {
            $ubicacionOBJ = $this->getUbicacionDAO()->getUbicacion($idUbicacion);
            $form->bind($ubicacionOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'ubicaciones' => $this->getUbicacionDAO()->getUbicaciones(),
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE EMPLEADO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ubicacionOBJ = new Ubicacion($form->getData());
                $nombreUbicacion = '';
                if ($sesionUbicacion = $this->identity()) {
                    $nombreUbicacion = $sesionUbicacion->login;
                }
                $ubicacionOBJ->setEstado('Activo');
                $ubicacionOBJ->setRegistradoPor($nombreUbicacion);
                $ubicacionOBJ->setFechaHoraReg(date('Y-m-d H:i:s'));
                $ubicacionOBJ->setModificadoPor('');
                $ubicacionOBJ->setFechaHoraMod('0000-00-00 00:00:00');
                $this->getUbicacionDAO()->guardar($ubicacionOBJ);
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'ubicacion',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'ubicacion',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('talento-humano/ubicacion/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idUbicacion = (int) $this->params()->fromQuery('idUbicacion', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idUbicacion);
        $request = $this->getRequest();
        if ($request->isPost()) {
//            $nombreUbicacion = '';
//            if ($sesionUbicacion = $this->identity()) {
//                $nombreUbicacion = $sesionUbicacion->login;
//            }
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ubicacionOBJ = new Ubicacion($form->getData());
//                $nombreUbicacion = '';
//                if ($sesionUbicacion = $this->identity()) {
//                    $nombreUbicacion = $sesionUbicacion->login;
//                }
//                $ubicacionOBJ->setModificadoPor($nombreUbicacion);
//                $ubicacionOBJ->setFechaHoraMod(date('Y-m-d H:i:s'));

                $this->getUbicacionDAO()->guardar($ubicacionOBJ);
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'ubicacion',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'ubicacion',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('talento-humano/ubicacion/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idUbicacion = (int) $this->params()->fromQuery('idUbicacion', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idUbicacion);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('talento-humano/ubicacion/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarUbicacionAction() {
//        $filtro = "cliente.estado = 'Registrado'";
        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
        $filtro = "ubicacion.pk_ubicacion_id NOT IN (SELECT pregunta_ubicacion.fk_ubicacion_id FROM pregunta_ubicacion WHERE pregunta_ubicacion.fk_pregunta_id = $idPregunta)";
        $view = new ViewModel(array(
            'ubicaciones' => $this->getUbicacionDAO()->getUbicaciones($filtro),
            'idPregunta' => $idPregunta
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getMunicipiosAction() {
        $idDepartamento = (int) $this->params()->fromQuery('idDepartamento', 0);
        if ($idDepartamento == 0) {
            return 0;
        }

        $view = new ViewModel(array(
            'municipios' => $this->getUbicacionDAO()->getMunicipios('idDepartamento = ' . $idDepartamento)
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getCentrosPobladosAction() {
        $idMunicipio = (int) $this->params()->fromQuery('idMunicipio', 0);
        if ($idMunicipio == 0) {
            return 0;
        }

        $view = new ViewModel(array(
            'centrospoblados' => $this->getUbicacionDAO()->getCentrosPoblados('fk_municipio_id = ' . $idMunicipio)
        ));
        $view->setTerminal(true);
        return $view;
    }

}
