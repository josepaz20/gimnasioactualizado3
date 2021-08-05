<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Hotspot\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Hotspot\Formularios\BusquedasForm;
use Hotspot\Formularios\RegistroForm;
use Hotspot\Modelo\Entidades\Registro;
use Hotspot\Modelo\Entidades\Busqueda;

class AdministracionController extends AbstractActionController {

    private $hotspotDAO;
    private $tipoidentificacionDAO;

//------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'idUsuario' => $sesionUsuario->pk_usuario_id,
                'idSucursal' => $sesionUsuario->idSucursal,
                'idEmpleado' => $sesionUsuario->fk_empleado_id,
                'idRol' => $sesionUsuario->fk_rol_id,
                'rol' => $sesionUsuario->rol,
                'nombresapellidos' => substr(trim($sesionUsuario->nombresapellidos), 0, 20),
            );
        } else {
            $infoSession = array(
                'idUsuario' => 0,
                'idSucursal' => '',
                'idEmpleado' => 0,
                'idRol' => 0,
                'rol' => '',
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

//------------------------------------------------------------------------------    

    public function getHotspotDAO() {
        if (!$this->hotspotDAO) {
            $sm = $this->getServiceLocator();
            $this->hotspotDAO = $sm->get('Hotspot\Modelo\DAO\RegistroDAO');
        }
        return $this->hotspotDAO;
    }

    public function getTipoIdentificacionDAO() {
        if (!$this->tipoidentificacionDAO) {
            $sm = $this->getServiceLocator();
            $this->tipoidentificacionDAO = $sm->get('Configuracion\Modelo\DAO\TipoIdentificacionDAO');
        }
        return $this->tipoidentificacionDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($accion = '', $idHotspot = 0) {
        $tiposidentificacion = $this->getTipoIdentificacionDAO()->getTiposIdentificacion();
        foreach ($tiposidentificacion as $tipoidentificacionOBJ) {
            $tiposIdentificacionSelect[$tipoidentificacionOBJ->getIdTipoIdentificacion()] = $tipoidentificacionOBJ->getTipo();
        }
        $form = new HotspotForm($accion, $tiposIdentificacionSelect);
        if ($idHotspot != 0) {
            $hotspotOBJ = $this->getHotspotDAO()->getHotspot($idHotspot);
            $form->bind($hotspotOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $filtro = '';
        $request = $this->getRequest();
        $formBusquedas = new BusquedasForm('index');
        if ($request->isGet()) {
            $authFormFilters = new Busqueda();
            $formBusquedas->setInputFilter($authFormFilters->getInputFilter());
            $formBusquedas->setData($request->getQuery());
            if ($formBusquedas->isValid()) {
                $busquedaOBJ = new Busqueda($formBusquedas->getData());
                $filtro = $busquedaOBJ->getFiltroBusqueda();
            }
        }
        return new ViewModel(array(
            'formBusquedas' => $formBusquedas,
            'hotspot' => $this->getHotspotDAO()->getHotspot($filtro)
        ));
    }

//------------------------------------------------------------------------------    

    public function editarAction() {
        $accion = 'editar';
        $idHotspot = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($accion, $idHotspot);
        $request = $this->getRequest();
        $session = $this->getInfoSesionUsuario();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $clienteOBJ = new Hotspot($form->getData());
                $clienteOBJ->setModificadopor($session['nombresapellidos']);
                $clienteOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                try {
                    $this->getHotspotDAO()->editar($clienteOBJ);
                    $this->flashMessenger()->addSuccessMessage('CLIENTE ACTUALIZADO EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTUALIZAR CLIENTE - AdministracionController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('CLIENTE NO ACTUALIZADO EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form,
            'idRol' => $session['idRol'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function detalleAction() {
        $accion = 'detalle';
        $idHotspot = (int) $this->params()->fromQuery('idHotspot', 0);
        $form = $this->getFormulario($accion, $idHotspot);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function getinfoclienteAction() {
        $identificacion = $this->params()->fromQuery('identificacion', 0);
        $idHotspot = $this->params()->fromQuery('idHotspot', 0);
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $view = new ViewModel();
        $error = 0;
        if ($identificacion != 0 && $idHotspot == 0) {
            $hotspotOBJ = $this->getHotspotDAO()->getHotspotByIdentificacion($identificacion);
        } else {
            $hotspotOBJ = $this->getHotspotDAO()->getHotspot($idHotspot);
        }
        if ($hotspotOBJ->getIdHotspot() == NULL) {
            $filtro = "identificacion LIKE '%$identificacion%'";
            $view->setVariables(array(
                'hotspot' => $this->getHotspotDAO()->getHotspot($filtro),
            ));
            $view->setTemplate('hotspot/administracion/seleccionar');
            $view->setTerminal(true);
            $html = $viewRender->render($view);
            $seleccionar = 1;
        } else {
            $seleccionar = 0;
            $html = $hotspotOBJ->getArrayCopy();
            $html['direcciones'] = $this->getHotspotDAO()->getDireccionesHotspot($hotspotOBJ->getIdHotspot());
        }
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
            'error' => $error,
            'html' => $html,
            'seleccionar' => $seleccionar,
        ));
        return $jsonModel;
    }

//------------------------------------------------------------------------------    

    public function existeidentificacionAction() {
        $error = 0;
        $existe = 1;
        $identificacion = $this->params()->fromQuery('identificacion', '');
        if ($identificacion != '') {
            $existe = $this->getHotspotDAO()->existeIdentificacion($identificacion);
        } else {
            $error = 1;
        }
        return new JsonModel(array(
            'error' => $error,
            'existe' => $existe,
            'identificacion' => $identificacion,
        ));
    }

//------------------------------------------------------------------------------    
}
