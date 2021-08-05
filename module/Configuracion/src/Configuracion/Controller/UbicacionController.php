<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Configuracion\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Configuracion\Formularios\UbicacionForm;
use Configuracion\Formularios\BusquedasForm;
use Configuracion\Modelo\Entidades\CentroPoblado;

class UbicacionController extends AbstractActionController {

    private $ubicacionDAO;

//------------------------------------------------------------------------------    

    public function getUbicacionDAO() {
        if (!$this->ubicacionDAO) {
            $sm = $this->getServiceLocator();
            $this->ubicacionDAO = $sm->get('Configuracion\Modelo\DAO\UbicacionDAO');
        }
        return $this->ubicacionDAO;
    }

//------------------------------------------------------------------------------    

    function getFormulario($action = '', $idCentroPoblado = 0) {
        $departamentos = $this->getUbicacionDAO()->getDepartamentos();
        $departamentosSelect = array();
        foreach ($departamentos as $dpto) {
            $departamentosSelect[$dpto->getPk_departamento_id()] = $dpto->getDepartamento();
        }
        if ($idCentroPoblado != 0) {
            $ubicacionOBJ = $this->getUbicacionDAO()->getUbicacion($idCentroPoblado);
            $municipios = $this->getUbicacionDAO()->getMunicipios('fk_departamento_id = ' . $ubicacionOBJ->getPk_departamento_id());
            $centrosPoblados = $this->getUbicacionDAO()->getCentrosPoblados('fk_municipio_id = ' . $ubicacionOBJ->getPk_municipio_id());
            $municipiosSelect = array();
            foreach ($municipios as $mcpo) {
                $municipiosSelect[$mcpo->getPk_municipio_id()] = $mcpo->getMunicipio();
            }
            $pobladosSelect = array();
            foreach ($centrosPoblados as $poblado) {
                $pobladosSelect[$poblado->getPk_centro_poblado_id()] = $poblado->getCentropoblado();
            }
            $form = new UbicacionForm($action, $departamentosSelect, $municipiosSelect, $pobladosSelect);
            $form->bind($ubicacionOBJ);
        } else {
            $form = new UbicacionForm($action, $departamentosSelect);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $idDepartamento = (int) $this->params()->fromRoute('id1', 8);
        $idMunicipio = (int) $this->params()->fromRoute('id2', 362);
        $departamentos = $this->getUbicacionDAO()->getDepartamentos();
        $departamentosSelect = array();
        foreach ($departamentos as $dpto) {
            $departamentosSelect[$dpto->getPk_departamento_id()] = $dpto->getDepartamento();
        }
        $municipios = $this->getUbicacionDAO()->getMunicipios('fk_departamento_id = ' . $idDepartamento);
        $municipiosSelect = array();
        foreach ($municipios as $mcpo) {
            $municipiosSelect[$mcpo->getPk_municipio_id()] = $mcpo->getMunicipio();
        }
        $formBusquedas = new BusquedasForm($departamentosSelect, $municipiosSelect);
        $formBusquedas->get('departamentoBusq')->setValue($idDepartamento);
        $formBusquedas->get('municipioBusq')->setValue($idMunicipio);
        return new ViewModel(array(
            'ubicaciones' => $this->getUbicacionDAO()->getUbicacionCentrosPoblados($idMunicipio),
            'formBusquedas' => $formBusquedas,
        ));
    }

    public function addAction() {
        $action = 'add';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $centroPobladoOBJ = new CentroPoblado(array(
                'fk_municipio_id' => (int) $this->params()->fromPost('pk_municipio_id', 0),
                'centropoblado' => $this->params()->fromPost('centropoblado', ''),
            ));
            if ($this->getUbicacionDAO()->guardarCentroPoblado($centroPobladoOBJ) > 0) {
                $this->flashMessenger()->addSuccessMessage('CENTRO POBLADO REGISTRADO EN JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('CENTRO POBLADO NO REGISTRADO EN JOSANDRO');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'ubicacion',
                        'action' => 'index',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/ubicacion/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

    public function editAction() {
        $action = 'edit';
        $idCentroPoblado = (int) $this->params()->fromQuery('idCentroPoblado', 0);
        $form = $this->getFormulario($action, $idCentroPoblado);
        $request = $this->getRequest();
        $ajax = true;
        if ($request->isPost()) {
            $centroPobladoOBJ = new CentroPoblado(array(
                'pk_centro_poblado_id' => (int) $this->params()->fromPost('pk_centro_poblado_id', -1),
                'fk_municipio_id' => (int) $this->params()->fromPost('pk_municipio_id', 0),
                'centropoblado' => $this->params()->fromPost('centropoblado', ''),
            ));
            if ($this->getUbicacionDAO()->guardarCentroPoblado($centroPobladoOBJ) > 0) {
                $this->flashMessenger()->addSuccessMessage('INFORMACION DEL CENTRO POBLADO ACTUALIZADA EN JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('INFORMACION DEL CENTRO POBLADO NO ACTUALIZADA EN JOSANDRO');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'ubicacion',
                        'action' => 'index',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/ubicacion/formulario');
        $view->setTerminal($ajax);
        return $view;
    }

//------------------------------------------------------------------------------    

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
            'municipios' => $this->getUbicacionDAO()->getMunicipios('fk_departamento_id = ' . $idDepartamento)
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
