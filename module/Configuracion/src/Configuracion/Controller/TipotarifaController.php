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
use Configuracion\Formularios\BusquedasForm;
use Configuracion\Formularios\TipoTarifaForm;
use Configuracion\Modelo\Entidades\TipoTarifa;

class TipotarifaController extends AbstractActionController {

    private $tipotarifaDAO;
    private $tiposervicioDAO;

//------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'idUsuario' => $sesionUsuario->pk_usuario_id,
                'idSucursal' => $sesionUsuario->idSucursal,
                'nombresapellidos' => substr(trim($sesionUsuario->nombresapellidos), 0, 20),
            );
        } else {
            $infoSession = array(
                'idUsuario' => 0,
                'idSucursal' => '',
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

//------------------------------------------------------------------------------    

    public function getTipoTarifaDAO() {
        if (!$this->tipotarifaDAO) {
            $sm = $this->getServiceLocator();
            $this->tipotarifaDAO = $sm->get('Configuracion\Modelo\DAO\TipoTarifaDAO');
        }
        return $this->tipotarifaDAO;
    }

    public function getTipoServicioDAO() {
        if (!$this->tiposervicioDAO) {
            $sm = $this->getServiceLocator();
            $this->tiposervicioDAO = $sm->get('Configuracion\Modelo\DAO\TipoServicioDAO');
        }
        return $this->tiposervicioDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($accion = '', $idTipoTarifa = 0) {
        $tiposservicio = $this->getTipoServicioDAO()->getTiposServicio();
        $listatiposservicio = array();
        foreach ($tiposservicio as $tipo) {
            $listatiposservicio[$tipo->getIdTipoServicio()] = $tipo->getTipo();
        }
        $form = new TipoTarifaForm($accion, $listatiposservicio);
        if ($idTipoTarifa != 0) {
            $tipoTarifaOBJ = $this->getTipoTarifaDAO()->getTipoTarifa($idTipoTarifa);
            $form->bind($tipoTarifaOBJ);
        }
        return $form;
    }

    function getFormularioBusquedas() {
        $tiposservicio = $this->getTipoServicioDAO()->getTiposServicio();
        $tiposServicioSelect = array();
        foreach ($tiposservicio as $tiposervicioOBJ) {
            $tiposServicioSelect[$tiposervicioOBJ->getIdTipoServicio()] = $tiposervicioOBJ->getTipo();
        }
        $form = new BusquedasForm('index', $tiposServicioSelect);
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $formBusquedas = $this->getFormularioBusquedas();
        $request = $this->getRequest();
        $idTipoServicio = 0;
        if ($request->isGet()) {
            if ($this->params()->fromQuery('idTipoServicioFiltro', -1) != -1) {
                $formBusquedas->setData($request->getQuery());
                if ($formBusquedas->isValid()) {
                    $datosbusqueda = $formBusquedas->getData();
                    $idTipoServicio = $datosbusqueda['idTipoServicioFiltro'];
                }
            }
        }
        return new ViewModel(array(
            'formBusquedas' => $formBusquedas,
            'tipos' => $this->getTipoTarifaDAO()->getTiposTarifa($idTipoServicio),
        ));
    }

//------------------------------------------------------------------------------    

    public function registrarAction() {
        $accion = 'registrar';
        $form = $this->getFormulario($accion);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $tipoTarifaOBJ = new TipoTarifa($form->getData());
                $sesion = $this->getInfoSesionUsuario();
                $tipoTarifaOBJ->setRegistradopor($sesion['nombresapellidos']);
                $tipoTarifaOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                try {
                    $this->getTipoTarifaDAO()->guardar($tipoTarifaOBJ);
                    $this->flashMessenger()->addSuccessMessage('TIPO DE TARIFA REGISTRADO EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  REGISTRAR TIPO TARIFA - TipotarifaController->registrar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('TIPO DE TARIFA NO REGISTRADO EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editarAction() {
        $accion = 'editar';
        $idTipoTarifa = (int) $this->params()->fromQuery('idTipoTarifa', 0);
        $form = $this->getFormulario($accion, $idTipoTarifa);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $tipoTarifaOBJ = new TipoTarifa($form->getData());
                $sesion = $this->getInfoSesionUsuario();
                $tipoTarifaOBJ->setModificadopor($sesion['nombresapellidos']);
                $tipoTarifaOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                try {
                    $this->getTipoTarifaDAO()->guardar($tipoTarifaOBJ);
                    $this->flashMessenger()->addSuccessMessage('LOS CAMBIOS FUERON GUARDADOS EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  EDITAR TIPO TARIFA - TipotarifaController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('LOS CAMBIOS NO FUERON GUARDADOS EN JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function eliminarAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idTipoTarifa = (int) $this->params()->fromPost('idTipoTarifa', 0);
            try {
                $this->getTipoTarifaDAO()->eliminar($idTipoTarifa);
                $this->flashMessenger()->addSuccessMessage('TIPO DE TARIFA ELIMINADO EN JOSANDRO');
            } catch (\Exception $ex) {
                $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  ELIMINAR TIPO TARIFA - TipotarifaController->editar \n"
                        . $ex->getMessage()
                        . "\n *********************************************************************** \n";
                $file = fopen("C:josandro.log", "a");
                fwrite($file, $msgLog);
                fclose($file);
                $this->flashMessenger()->addErrorMessage('TIPO DE TARIFA NO ELIMINADO EN JOSANDRO');
            }
            return $this->redirect()->toUrl('index');
        }
        $accion = 'eliminar';
        $idTipoTarifa = (int) $this->params()->fromQuery('idTipoTarifa', 0);
        $form = $this->getFormulario($accion, $idTipoTarifa);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function detalleAction() {
        $accion = 'detalle';
        $idTipoTarifa = (int) $this->params()->fromQuery('idTipoTarifa', 0);
        $form = $this->getFormulario($accion, $idTipoTarifa);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------
}
