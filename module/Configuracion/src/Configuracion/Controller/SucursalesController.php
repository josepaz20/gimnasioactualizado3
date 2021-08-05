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
use Configuracion\Formularios\SucursalForm;
use Configuracion\Modelo\Entidades\Sucursal;
use Comercial\Formularios\UbicacionForm;

class SucursalesController extends AbstractActionController {

    private $sucursalDAO;
    protected $ubicacionDAO;

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'nombresapellidos' => $sesionUsuario->nombresapellidos,
            );
        } else {
            $infoSession = array(
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

    public function getNombreApellidoUsuario() {
        $infoSession = $this->getInfoSesionUsuario();
        return substr(trim($infoSession['nombresapellidos']), 0, 20);
    }

//------------------------------------------------------------------------------    

    public function getSucursalDAO() {
        if (!$this->sucursalDAO) {
            $sm = $this->getServiceLocator();
            $this->sucursalDAO = $sm->get('Configuracion\Modelo\DAO\SucursalDAO');
        }
        return $this->sucursalDAO;
    }

    public function getUbicacionDAO() {
        if (!$this->ubicacionDAO) {
            $sm = $this->getServiceLocator();
            $this->ubicacionDAO = $sm->get('Comercial\Modelo\DAO\UbicacionDAO');
        }
        return $this->ubicacionDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idSucursal = 0) {
        $form = new SucursalForm($action);
        if ($idSucursal != 0) {
            $sucursalOBJ = $this->getSucursalDAO()->getSucursal($idSucursal);
            $form->bind($sucursalOBJ);
        }
        return $form;
    }

    function getFormularioUbicacion($idCentroPoblado = 0) {
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
            $form = new UbicacionForm($departamentosSelect, $municipiosSelect, $pobladosSelect);
            $form->bind($ubicacionOBJ);
        } else {
            $form = new UbicacionForm($departamentosSelect);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function administracionAction() {
        return new ViewModel(array(
            'sucursales' => $this->getSucursalDAO()->getSucursales(),
        ));
    }

//------------------------------------------------------------------------------    

    public function addAction() {
        $action = 'add';
        $form = $this->getFormulario($action);
        $formUbicacion = $this->getFormularioUbicacion();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $sucursalOBJ = new Sucursal($form->getData());
                $sucursalOBJ->setRegistradopor($this->getNombreApellidoUsuario());
                $sucursalOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                if ($this->getSucursalDAO()->guardar($sucursalOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('SUCURSAL REGISTRADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('SUCURSAL NO REGISTRADA EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'sucursales',
                        'action' => 'administracion',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
            'formUbicacion' => $formUbicacion,
        ));
        $view->setTemplate('configuracion/sucursales/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editAction() {
        $action = 'edit';
        $idSucursal = (int) $this->params()->fromQuery('idSucursal', 0);
        $form = $this->getFormulario($action, $idSucursal);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $sucursalOBJ = new Sucursal($form->getData());
                $sucursalOBJ->setModificadopor($this->getNombreApellidoUsuario());
                $sucursalOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getSucursalDAO()->guardar($sucursalOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DE LA SUCURSAL ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DE LA SUCURSAL NO ACTUALIZADA EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'sucursales',
                        'action' => 'administracion',
            ));
        }
        $formUbicacion = $this->getFormularioUbicacion($form->get('fk_centro_poblado_id')->getValue());
        $view = new ViewModel(array(
            'form' => $form,
            'formUbicacion' => $formUbicacion,
        ));
        $view->setTemplate('configuracion/sucursales/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idSucursal = (int) $this->params()->fromPost('pk_barrio_id', 0);
            if ($this->getSucursalDAO()->eliminar($idSucursal) > 0) {
                $this->flashMessenger()->addSuccessMessage('SUCURSAL ELIMINADO EN JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('SUCURSAL NO ELIMINADO EN JOSANDRO');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'sucursales',
                        'action' => 'administracion',
            ));
        }
        $idSucursal = (int) $this->params()->fromQuery('idSucursal', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idSucursal);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/sucursales/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function detailAction() {
        $action = 'detail';
        $idSucursal = (int) $this->params()->fromQuery('idSucursal', 0);
        $form = $this->getFormulario($action, $idSucursal);
        $formUbicacion = $this->getFormularioUbicacion($form->get('fk_centro_poblado_id')->getValue());
        $view = new ViewModel(array(
            'form' => $form,
            'formUbicacion' => $formUbicacion,
        ));
        $view->setTemplate('configuracion/sucursales/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------
}
