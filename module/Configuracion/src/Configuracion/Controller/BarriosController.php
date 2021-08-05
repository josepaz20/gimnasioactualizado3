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
use Configuracion\Formularios\BarrioForm;
use Configuracion\Modelo\Entidades\Barrio;

class BarriosController extends AbstractActionController {

    private $barrioDAO;
    private $zonaDAO;
    private $sucursalDAO;

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

    public function getBarrioDAO() {
        if (!$this->barrioDAO) {
            $sm = $this->getServiceLocator();
            $this->barrioDAO = $sm->get('Configuracion\Modelo\DAO\BarrioDAO');
        }
        return $this->barrioDAO;
    }

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

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idBarrio = 0) {
        $listaZonas = array();
        $zonas = $this->getZonaDAO()->getZonas(array('zona.estado' => 'Activo'));
        foreach ($zonas as $zona) {
            $zonaOBJ = $zona['zonaOBJ'];
            $listaZonas[$zonaOBJ->getPk_zona_id()] = $zonaOBJ->getZona();
        }
        $sucursales = $this->getSucursalDAO()->getSucursalesSelect();
        $listaSucursales = array();
        foreach ($sucursales as $sucursal) {
            $listaSucursales[$sucursal->getIdSucursal()] = $sucursal->getSucursal();
        }
        $form = new BarrioForm($action, $listaZonas, $listaSucursales);
        if ($idBarrio != 0) {
            $barrioOBJ = $this->getBarrioDAO()->getBarrio($idBarrio);
            $form->bind($barrioOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function administracionAction() {
        return new ViewModel(array(
            'barrios' => $this->getBarrioDAO()->getBarrios(),
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
                $barrioOBJ = new Barrio($form->getData());
                $barrioOBJ->setRegistradopor($this->getNombreApellidoUsuario());
                $barrioOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                $barrioOBJ->setFechahoramod('0000-00-00 00:00:00');
                if ($this->getBarrioDAO()->guardar($barrioOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('BARRIO REGISTRADO EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('BARRIO NO REGISTRADO EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'barrios',
                        'action' => 'administracion',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/barrios/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editAction() {
        $action = 'edit';
        $idBarrio = (int) $this->params()->fromQuery('idBarrio', 0);
        $form = $this->getFormulario($action, $idBarrio);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $barrioOBJ = new Barrio($form->getData());
                $barrioOBJ->setModificadopor($this->getNombreApellidoUsuario());
                $barrioOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                if ($this->getBarrioDAO()->guardar($barrioOBJ) == 1) {
                    $this->flashMessenger()->addSuccessMessage('INFORMACION DE LA BARRIO ACTUALIZADA EN JOSANDRO');
                } else {
                    $this->flashMessenger()->addErrorMessage('INFORMACION DE LA BARRIO NO ACTUALIZADA EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADO NO ES VALIDA');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'barrios',
                        'action' => 'administracion',
            ));
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/barrios/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idBarrio = (int) $this->params()->fromPost('pk_barrio_id', 0);
            if ($this->getBarrioDAO()->eliminar($idBarrio) > 0) {
                $this->flashMessenger()->addSuccessMessage('BARRIO ELIMINADO EN JOSANDRO');
            } else {
                $this->flashMessenger()->addErrorMessage('BARRIO NO ELIMINADO EN JOSANDRO');
            }
            return $this->redirect()->toRoute('configuracion/default', array(
                        'controller' => 'barrios',
                        'action' => 'administracion',
            ));
        }
        $idBarrio = (int) $this->params()->fromQuery('idBarrio', 0);
        $action = 'delete';
        $form = $this->getFormulario($action, $idBarrio);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/barrios/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function detailAction() {
        $action = 'detail';
        $idBarrio = (int) $this->params()->fromQuery('idBarrio', 0);
        $form = $this->getFormulario($action, $idBarrio);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('configuracion/barrios/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function getBarriosAction() {
        $idZona = (int) $this->params()->fromQuery('idZona', 0);
        if ($idZona == 0) {
            return 0;
        }
        $view = new ViewModel(array(
            'barrios' => $this->getBarrioDAO()->getBarrios(array('barrio.fk_zona_id' => $idZona))
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------
}
