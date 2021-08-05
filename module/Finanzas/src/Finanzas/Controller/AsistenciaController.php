<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Finanzas\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Finanzas\Formularios\BusquedasForm;
use Dompdf\Dompdf;
use Finanzas\Formularios\AsistenciaForm;
use Finanzas\Modelo\Entidades\Asistencia;
use Finanzas\Modelo\Entidades\Busqueda;

class AsistenciaController extends AbstractActionController {

    private $servicioDAO;
    private $finanzasDAO;
    private $tipoidentificacionDAO;
    
    private $rutaArchivos = 'D:JOSANDRO';

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

    public function getServicioDAO() {
        if (!$this->servicioDAO) {
            $sm = $this->getServiceLocator();
            $this->servicioDAO = $sm->get('Servicios\Modelo\DAO\ServicioDAO');
        }
        return $this->servicioDAO;
    }

    //------------------------------------------------------------------------------    

    function getFormulario($accion = '', $idRegistro = 0) {
        $form = new AsistenciaForm($accion);
        if ($idRegistro != 0) {
            $materialOBJ = $this->getAsistenciaDAO()->getRegistro($idRegistro);
            $form->bind($materialOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function getAsistenciaDAO() {
        if (!$this->finanzasDAO) {
            $sm = $this->getServiceLocator();
            $this->finanzasDAO = $sm->get('Finanzas\Modelo\DAO\AsistenciaDAO');
        }
        return $this->finanzasDAO;
    }

    public function getOrdenTrabajoDAO() {
        if (!$this->ordenTrabajoDAO) {
            $sm = $this->getServiceLocator();
            $this->ordenTrabajoDAO = $sm->get('Ordenestrabajo\Modelo\DAO\OrdenTrabajoDAO');
        }
        return $this->ordenTrabajoDAO;
    }

//------------------------------------------------------------------------------

    public function detalleAction() {
        $session = $this->getInfoSesionUsuario();
        $accion = 'detalle';
        $idRegistro = (int) $this->params()->fromQuery('idRegistro', 0);
        $form = $this->getFormulario($accion, $idRegistro);
        $view = new ViewModel(array(
            'form' => $form,
            'empleado' => $session['nombresapellidos'],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function indexAction() {

        $filtro = '';
        return new ViewModel(array(
            'registros' => $this->getAsistenciaDAO()->getRegistros($filtro),
        ));
    }

//------------------------------------------------------------------------------ / 

    public function registrarAction() {
          
        $action = 'registrar';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $conarchivo = false;
                $archivo = '';
                $respaldo = "comprobante";
                $files = $request->getFiles()->toArray();
                if (array_key_exists($respaldo, $files)) {
                    $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                    $filesize = new \Zend\Validator\File\Size(array('max' => 5242880)); //  5 MB
                    $extension = new \Zend\Validator\File\Extension(array('extension' => array('pdf', 'png', 'jpeg', 'jpg')));
                    $httpadapter->setDestination($this->rutaArchivos);
                    $httpadapter->setValidators(array($filesize, $extension), $files[$respaldo]['name']);
                    if ($httpadapter->isValid($respaldo)) {
                        $ext = pathinfo($files[$respaldo]['name'], PATHINFO_EXTENSION);
                        $archivo = strtoupper(md5(rand() . $files[$respaldo]['name'] . date('YmdHis'))) . '.' . $ext;
                        echo 'foto';
                        var_dump($archivo2);
                        exit();
                        $httpadapter->addFilter('File\Rename', array(
                            'target' => $this->rutaArchivos . '/' . $archivo,
                        ));
                        try {
                            if ($httpadapter->receive($files[$respaldo]['name'])) {
                                $conarchivo = true;
                            } else {
                                $this->flashMessenger()->addErrorMessage('RESPALDO DE LEGALIZACION NO REGISTRADO EN SERVIDOR: ' . $tiporespaldo);
                            }
                        } catch (\Exception $ex) {
                            $this->flashMessenger()->addErrorMessage('RESPALDO DE LEGALIZACION NO REGISTRADO EN SERVIDOR: ' . $tiporespaldo);
                            $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  SUBIR ARCHIVO LEGALIZACION - LegalizacionController->registrar \n"
                                    . $ex->getMessage()
                                    . "\n *********************************************************************** \n";
                            $file = fopen("/home/josandro.log", "a");
                            fwrite($file, $msgLog);
                            fclose($file);
                        }
                    } else {
                        $this->flashMessenger()->addErrorMessage('ARCHIVO DE RESPALDO NO VALIDO: ' . $tiporespaldo);
                    }
                }

                $session = $this->getInfoSesionUsuario();
                $registroOBJ = new MovimientoCaja($form->getData());

                $registroOBJ->setEstado('Registrado');
                if ($conarchivo) {

                    $registroOBJ->setComprobante($archivo);
                }
                $session = $this->getInfoSesionUsuario();
                $registroOBJ = new Movimientocaja($form->getData());

                try {                  
                    $this->getFinanzasDAO()->registrar($registroOBJ);
                    $this->flashMessenger()->addSuccessMessage('CLIENTE REGISTRADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTUALIZAR CLIENTE - RegistroController->registrar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('CLIENTE NO REGISTRADO EN GIMNASIO GYM-JAM');
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
                $registroOBJ = new Movimientocaja($form->getData());
                try {
                    $this->getFinanzasDAO()->editar($registroOBJ);
                    $this->flashMessenger()->addSuccessMessage('CLIENTE ACTUALIZADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog .= "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTUALIZAR CLIENTE - RegistroController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('CLIENTE NO ACTUALIZADO EN GIMNASIO GYM-JAM');
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
                    $this->getFinanzasDAO()->eliminar($idPregunta);
                    $this->flashMessenger()->addSuccessMessage('CLIENTE ELIMINADO DE  GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ELIMINAR CLIENTE - RegistroController->eliminar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('CLIENTE NO ELIMINADO DE GIMNASIO GYM-JAM');
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
                    $this->getFinanzasDAO()->activar($idRegistro);
                    $this->flashMessenger()->addSuccessMessage('CLIENTE ACTIVADO EN GIMNASIO GYM-JAM');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTIVAR CLIENTE - RegistroController->activar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('CLIENTE NO ACTIVADO EN GIMNASIO GYM-JAM');
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

    public function imprimirAction() {
        $idRegistro = (int) $this->params()->fromRoute('id1', 0);
        $plantillaHTML = file_get_contents('module/Finanzas/view/finanzas/cajaspersonales/imprimircontrato.html');
        $registro = $this->getFinanzasDAO()->getServicioImprimir($idRegistro);
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

//------------------------------------------------------------------------------    

