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
use Hotspot\Formularios\RespuestaForm;
use Hotspot\Modelo\Entidades\Respuesta;
use Hotspot\Modelo\Entidades\Busqueda;

class RespuestaController extends AbstractActionController {

    private $respuestaDAO;
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

    function getFormulario($accion = '', $idRespuesta = 0) {
        $form = new RespuestaForm($accion);
        if ($idRespuesta != 0) {
            $respuestaOBJ = $this->getRespuestaDAO()->getRespuesta($idRespuesta);
            $form->bind($respuestaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function getRespuestaDAO() {
        if (!$this->respuestaDAO) {
            $sm = $this->getServiceLocator();
            $this->respuestaDAO = $sm->get('Hotspot\Modelo\DAO\RespuestaDAO');
        }
        return $this->respuestaDAO;
    }

//------------------------------------------------------------------------------

    public function detalleAction() {
        $accion = 'detalle';
        $idRespuesta = (int) $this->params()->fromQuery('idRespuesta', 0);
        $form = $this->getFormulario($accion, $idRespuesta);
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $filtro = '';
        return new ViewModel(array(
            'respuestas' => $this->getRespuestaDAO()->getRespuestas($filtro)
        ));
    }

//------------------------------------------------------------------------------    

    public function registrarAction() {
        $action = 'registrar';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $respuestaOBJ = new Respuesta($form->getData());
                $respuestaOBJ->setEstado('Activo');

                try {
                    $this->getRespuestaDAO()->registrar($respuestaOBJ);
                    $this->flashMessenger()->addSuccessMessage('RESPUESTA REGISTRADA EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  REGISTRAR RESPUESTA - RespuestaController->registrar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('RESPUESTA NO REGISTRADA EN JOSANDRO');
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
        $action = 'editar';
        $idRespuesta = (int) $this->params()->fromQuery('idRespuesta', 0);
        $form = $this->getFormulario($action, $idRespuesta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $respuestaOBJ = new Respuesta($form->getData());

                try {
                    $this->getRespuestaDAO()->editar($respuestaOBJ);
                    $this->flashMessenger()->addSuccessMessage('RESPUESTA ACTUALIZADA EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  EDITAR RESPUESTA - RespuestaController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('RESPUESTA NO ACTUALIZADA EN JOSANDRO');
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
        $action = 'eliminar';
        $idRespuesta = (int) $this->params()->fromQuery('idRespuesta', 0);
        $form = $this->getFormulario($action, $idRespuesta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idRespuesta = (int) $this->params()->fromPost('$idRespuesta', 0);
            if ($idRespuesta != 0) {
                $session = $this->getInfoSesionUsuario();
                

                try {
                    $this->getRespuestaDAO()->eliminar($idRespuesta);
                    $this->flashMessenger()->addSuccessMessage('RESPUESTA ELIMINADA DE  JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ELIMINAR RESPUESTA - RespuestaController->eliminar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("D:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('RESPUESTA NO ELIMINADA DE JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION NO ES VALIDA');
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
    public function activarAction() {
        $action = 'activar';
        $idRespuesta = (int) $this->params()->fromQuery('idRespuesta', 0);
        $form = $this->getFormulario($action, $idRespuesta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idRespuesta = (int) $this->params()->fromPost('idRespuesta', 0);
            if ($idRespuesta != 0) {
                $session = $this->getInfoSesionUsuario();

                try {
                    $this->getRespuestaDAO()->activar($idRespuesta);
                    $this->flashMessenger()->addSuccessMessage('RESPUESTA ACTIVADA DE  JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTIVAR  PREGUNTA - RespuestaController->activar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('RESPUESTA NO SCTIVADA DE JOSANDRO');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION NO ES VALIDAAAA');
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
}
