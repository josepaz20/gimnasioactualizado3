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
use Configuracion\Formularios\ZonaForm;
use Configuracion\Modelo\Entidades\Zona;

class ZonasController extends AbstractActionController {

    private $zonaDAO;
    private $sucursalDAO;
    private $ubicacionDAO;

//------------------------------------------------------------------------------    

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

    public function getZonaDAO() {
        if (!$this->zonaDAO) {
            $sm = $this->getServiceLocator();
            $this->zonaDAO = $sm->get('Configuracion\Modelo\DAO\ZonaDAO');
        }
        return $this->zonaDAO;
    }

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
            $this->ubicacionDAO = $sm->get('Configuracion\Modelo\DAO\UbicacionDAO');
        }
        return $this->ubicacionDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idZona = 0) {
        $sucursales = $this->getSucursalDAO()->getSucursalesSelect();
        $selectSucursales = array();
        foreach ($sucursales as $sucursal) {
            $selectSucursales[$sucursal->getIdSucursal()] = $sucursal->getSucursal();
        }
        $form = new ZonaForm($action, $selectSucursales);
        if ($idZona != 0) {
            $zonaOBJ = $this->getZonaDAO()->getZona($idZona);
            $form->bind($zonaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function administracionAction() {
        return new ViewModel(array(
            'zonas' => $this->getZonaDAO()->getZonas(),
        ));
    }

//------------------------------------------------------------------------------    

    public function addAction() {
        $action = 'add';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $zonaOBJ = new Zona($form->getData());
                $zonaOBJ->setRegistradopor($this->getNombreApellidoUsuario());
                $zonaOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                $zonaOBJ->setFechahoramod('0000-00-00 00:00:00');
                if ($this->getZonaDAO()->guardar($zonaOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('ZONA REGISTRADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('ZONA NO REGISTRADA EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'zonas',
                        'action' => 'administracion',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/zonas/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editAction() {
        $action = 'edit';
        $idZona = (int) $this->params()->fromQuery('idZona', 0);
        $form = $this->getFormulario($action, $idZona);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $zonaOBJ = new Zona($form->getData());
                $zonaOBJ->setModificadopor($this->getNombreApellidoUsuario());
                $zonaOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getZonaDAO()->guardar($zonaOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DE LA ZONA ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DE LA ZONA NO ACTUALIZADA EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'zonas',
                        'action' => 'administracion',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/zonas/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idZona = (int) $this->params()->fromPost('pk_zona_id', 0);
            try {
                $eliminado = $this->getZonaDAO()->eliminar($idZona);
            } catch (\Exception $ex) {
                var_dump($ex);
                $eliminado = 0;
            }
            if ($eliminado > 0) {
                $this->flashMessenger()->addSuccessMessage('ZONA ELIMINADA EN JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('ZONA NO ELIMINADA EN JOSANDRO');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'zonas',
                        'action' => 'administracion',
            ));
        }
        $idZona = (int) $this->params()->fromQuery('idZona', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idZona);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/zonas/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function detailAction() {
        $action = 'detail';
        $idZona = (int) $this->params()->fromQuery('idZona', 0);
        $form = $this->getFormulario($action, $idZona);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/zonas/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function getZonasAction() {
        $idSucursal = (int) $this->params()->fromQuery('idSucursal', 0);
        if ($idSucursal == 0) {
            return 0;
        }
        $view = new ViewModel(array(
            'zonas' => $this->getZonaDAO()->getZonas(array('zona.idSucursal' => $idSucursal)),
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------
}
