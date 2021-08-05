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
use Hotspot\Formularios\ProductoForm;
use Hotspot\Modelo\Entidades\Producto;
use Dompdf\Dompdf;
use Hotspot\Modelo\Entidades\Busqueda;

class ProductoController extends AbstractActionController {

    private $productoDAO;
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

    public function getTipoIdentificacionDAO() {
        if (!$this->tipoidentificacionDAO) {
            $sm = $this->getServiceLocator();
            $this->tipoidentificacionDAO = $sm->get('Configuracion\Modelo\DAO\TipoIdentificacionDAO');
        }
        return $this->tipoidentificacionDAO;
    }

    //------------------------------------------------------------------------------    

    function getFormulario($accion = '', $idRegistro = 0) {
        $form = new ProductoForm($accion);
        if ($idRegistro != 0) {
            $registroOBJ = $this->getProductoDAO()->getProducto($idRegistro);
            $form->bind($registroOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function getProductoDAO() {
        if (!$this->productoDAO) {
            $sm = $this->getServiceLocator();
            $this->productoDAO = $sm->get('Hotspot\Modelo\DAO\ProductoDAO');
        }
        return $this->productoDAO;
    }

//------------------------------------------------------------------------------

    public function detalleAction() {
        $session = $this->getInfoSesionUsuario();
        $accion = 'detalle';
        $idRegistro = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($accion, $idRegistro);
        $view = new ViewModel(array(
            'form' => $form,
            'idUsuario' => $session['idUsuario'],
            'empleado' => $session['nombresapellidos'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $filtro = '';
        return new ViewModel(array(
            'registros' => $this->getProductoDAO()->getRegistros($filtro)
        ));
    }

//------------------------------------------------------------------------------ / 

    public function registrarAction() {
        $session = $this->getInfoSesionUsuario();
        $accion = 'registrar';
        $form = $this->getFormulario($accion);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $registroOBJ = new Producto($form->getData());
                try {
                    $this->getProductoDAO()->registrar($registroOBJ);
                    $this->flashMessenger()->addSuccessMessage('PRODUCTO REGISTRADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  REGISTRAR PRODUCTO - ProductoController->registrar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PRODUCTO NO REGISTRADO EN GIMNASIO GYM-JAM');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form,
            'idUsuario' => $session['idUsuario'],
            'empleado' => $session['nombresapellidos'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------ /
    public function editarAction() {
        $session = $this->getInfoSesionUsuario();
        $accion = 'editar';
        $idRegistro = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($accion, $idRegistro);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $registroOBJ = new Producto($form->getData());
                try {
                    $this->getProductoDAO()->editar($registroOBJ);
                    $this->flashMessenger()->addSuccessMessage('PRODUCTO ACTUALIZADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTUALIZAR PRODUCTO - RegistroController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PRODUCTO NO ACTUALIZADO EN GIMNASIO GYM-JAM');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form,
            'idUsuario' => $session['idUsuario'],
            'empleado' => $session['nombresapellidos'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    
    public function eliminarAction() {
        $session = $this->getInfoSesionUsuario();
        $action = 'eliminar';
        $idRegistro = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($action, $idRegistro);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idPregunta = (int) $this->params()->fromPost('idRegistro', 0);
            if ($idPregunta != 0) {
                $session = $this->getInfoSesionUsuario();

                try {
                    $this->getProductoDAO()->eliminar($idPregunta);
                    $this->flashMessenger()->addSuccessMessage('PRODUCTO ELIMINADO DE GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ELIMINAR PRODUCTO - ProductoController->eliminar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PRODUCTO NO ELIMINADO DE GIMNASIO GYM-JAM');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
           
             'form' => $form,
            'idUsuario' => $session['idUsuario'],
            'empleado' => $session['nombresapellidos'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------ 
    public function activarAction() {
        $session = $this->getInfoSesionUsuario();
        $action = 'activar';
        $idRegistro = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($action, $idRegistro);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idRegistro = (int) $this->params()->fromPost('idRegistro', 0);
            if ($idRegistro != 0) {
                $session = $this->getInfoSesionUsuario();

                try {
                    $this->getProductoDAO()->activar($idRegistro);
                    $this->flashMessenger()->addSuccessMessage('PRODUCTO ACTIVADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTIVAR PRODUCTO - RegistroController->activar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PRODUCTO NO ACTUALIZADO EN GIMNASIO GYM-JAM');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
             'form' => $form,
            'idUsuario' => $session['idUsuario'],
            'empleado' => $session['nombresapellidos'],
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
            $hotspotOBJ = $this->getRegistroDAO()->getHotspotByIdentificacion($identificacion);
        } else {
            $hotspotOBJ = $this->getRegistroDAO()->getHotspot($idHotspot);
        }
        if ($hotspotOBJ->getIdHotspot() == NULL) {
            $filtro = "identificacion LIKE '%$identificacion%'";
            $view->setVariables(array(
                'hotspot' => $this->getRegistroDAO()->getHotspot($filtro),
            ));
            $view->setTemplate('hotspot/administracion/seleccionar');
            $view->setTerminal(true);
            $html = $viewRender->render($view);
            $seleccionar = 1;
        } else {
            $seleccionar = 0;
            $html = $hotspotOBJ->getArrayCopy();
            $html['direcciones'] = $this->getRegistroDAO()->getDireccionesHotspot($hotspotOBJ->getIdHotspot());
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
            $existe = $this->getRegistroDAO()->existeIdentificacion($identificacion);
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

    public function imprimirAction() {
        $idRegistro = (int) $this->params()->fromRoute('id1', 0);
        $plantillaHTML = file_get_contents('module/Hotspot/view/hotspot/producto/imprimircontrato.html');
        $registro = $this->getProductoDAO()->getServicioImprimir($idRegistro);
        if (is_array($registro)) {
            switch ($registro['idRegistro']) {
                case 1: // TESALIA
                    $registro['razonSocial'] = 'TORNET COMUNICACIONES S.A.S.';
                    $registro['nit'] = '901234188-9';
                    $registro['direccion'] = 'Carrera 8 No. 4-79 Barrio Acacias';
                    $registro['ubicacion'] = 'Tesalia Huila';
                    $registro['telefono'] = '316 2488523';
                    $registro['web'] = 'www.alfavision.com';
                    break;
            }
            $html = $this->reemplazarMarcadores($plantillaHTML, $registro);

            $this->imprimirPDF($html);
        }
    }

    public function imprimirPDF($plantilla = '') {
        require_once 'vendor/dompdf/autoload.inc.php';
        $dompdf = new Dompdf();
        $dompdf->loadHtml($plantilla);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('CONTRATO DE VENTA DE SERVICIOS', array('Attachment' => 0));
    }

    public function reemplazarMarcadores($plantilla = '', $marcadores = array()) {
        foreach ($marcadores as $campo => $vlr) {
            $plantilla = str_replace('{' . $campo . '}', $vlr, $plantilla);
        }
        return $plantilla;
    }

//------------------------------------------------------------------------------    
}
