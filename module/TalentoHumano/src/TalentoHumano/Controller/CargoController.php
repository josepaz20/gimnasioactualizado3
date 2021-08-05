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
use TalentoHumano\Formularios\CargoForm;
use TalentoHumano\Modelo\Entidades\Cargo;
use Zend\Json\Json;

class CargoController extends AbstractActionController {

    private $cargoDAO;

    public function getCargoDAO() {
        if (!$this->cargoDAO) {
            $sm = $this->getServiceLocator();
            $this->cargoDAO = $sm->get('TalentoHumano\Modelo\DAO\CargoDAO');
        }
        return $this->cargoDAO;
    }

    public function getContratoDAO() {
        if (!$this->contratoDAO) {
            $sm = $this->getServiceLocator();
            $this->contratoDAO = $sm->get('TalentoHumano\Modelo\DAO\ContratoDAO');
        }
        return $this->contratoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idCargo = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new CargoForm($action, $onsubmit, $required);
        if ($idCargo != 0) {
            $cargoOBJ = $this->getCargoDAO()->getCargo($idCargo);
            $form->bind($cargoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'cargos' => $this->getCargoDAO()->getCargos(),
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE CARGO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $cargoOBJ = new Cargo($form->getData());
                $nombreCargo = '';
//                $cargoOBJ->setRegistradoPor($nombreCargo);
//                $cargoOBJ->setFechaHoraReg(date('Y-m-d H:i:s'));
//                $cargoOBJ->setModificadoPor('');
//                $cargoOBJ->setFechaHoraMod('0000-00-00 00:00:00');
                $this->getCargoDAO()->guardar($cargoOBJ);
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'cargo',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'cargo',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('talento-humano/cargo/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idCargo = (int) $this->params()->fromQuery('idCargo', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE CARGO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idCargo);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $cargoOBJ = new Cargo($form->getData());
                $nombreCargo = '';
                if ($sesionCargo = $this->identity()) {
                    $nombreCargo = $sesionCargo->login;
                }
                $this->getCargoDAO()->guardar($cargoOBJ);
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'cargo',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'cargo',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('talento-humano/cargo/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idCargo = (int) $this->params()->fromQuery('idCargo', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idCargo);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('talento-humano/cargo/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarCargoAction() {
//        $filtro = "cliente.estado = 'Registrado'";
        // $filtro = "cargo.estado = 'Activo'";
        $view = new ViewModel(array(
            'cargos' => $this->getCargoDAO()->getCargos(),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getCargoAction() {
        $idCargo = (int) $this->params()->fromQuery('idCargo', 0);
        if (!$idCargo) {
            return 0;
        }
        $form = $this->getFormulario('buscar', '', $idCargo);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('talento-humano/cargo/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
