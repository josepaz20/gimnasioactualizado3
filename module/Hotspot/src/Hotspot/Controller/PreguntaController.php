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
use Hotspot\Formularios\PreguntaForm;
use Hotspot\Modelo\Entidades\Pregunta;
use Hotspot\Modelo\Entidades\Busqueda;

class PreguntaController extends AbstractActionController {

    private $preguntaDAO;
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

    function getFormulario($accion = '', $idPregunta = 0) {
        $form = new PreguntaForm($accion);
        if ($idPregunta != 0) {
            $preguntaOBJ = $this->getPreguntaDAO()->getPregunta($idPregunta);
            $form->bind($preguntaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function getPreguntaDAO() {
        if (!$this->preguntaDAO) {
            $sm = $this->getServiceLocator();
            $this->preguntaDAO = $sm->get('Hotspot\Modelo\DAO\PreguntaDAO');
        }
        return $this->preguntaDAO;
    }

//------------------------------------------------------------------------------

    public function detalleAction() {
        $accion = 'detalle';
        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
        $form = $this->getFormulario($accion, $idPregunta);
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
            'preguntas' => $this->getPreguntaDAO()->getPreguntas($filtro)
        ));
    }

//------------------------------------------------------------------------------    
//------------------------------------------------------------------------------    

    public function registrarAction() {
        $action = 'registrar';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $preguntaOBJ = new Pregunta($form->getData());

                try {
                    $this->getPreguntaDAO()->registrar($preguntaOBJ);
                    $this->flashMessenger()->addSuccessMessage('PREGUNTA REGISTRADA EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  REGISTRAR PREGUNTA - PreguntaController->registrar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PREGUNTA NO REGISTRADA EN JOSANDRO');
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
        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
        $form = $this->getFormulario($action, $idPregunta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $preguntaOBJ = new Pregunta($form->getData());

                try {
                    $this->getPreguntaDAO()->editar($preguntaOBJ);
                    $this->flashMessenger()->addSuccessMessage('PREGUNTA ACTUALIZADA EN JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  EDITAR PREGUNTA - PreguntaController->editar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PREGUNTA NO ACTUALIZADA EN JOSANDRO');
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
        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
        $form = $this->getFormulario($action, $idPregunta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idPregunta = (int) $this->params()->fromPost('idPregunta', 0);
            if ($idPregunta != 0) {
                $session = $this->getInfoSesionUsuario();

                try {
                    $this->getPreguntaDAO()->eliminar($idPregunta);
                    $this->flashMessenger()->addSuccessMessage('MATERIAL ELIMINADO DE  JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ELIMINAR MATERIAL - PreguntaController->eliminar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('MATERIAL NO ELIMINADO DE JOSANDRO');
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
        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
        $form = $this->getFormulario($action, $idPregunta);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $idPregunta = (int) $this->params()->fromPost('idPregunta', 0);
            if ($idPregunta != 0) {
                $session = $this->getInfoSesionUsuario();

                try {
                    $this->getPreguntaDAO()->activar($idPregunta);
                    $this->flashMessenger()->addSuccessMessage('PREGUNTA ACTUALIZADA DE  JOSANDRO');
                } catch (\Exception $ex) {
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  ACTUALIZAR PREGUNTA - PreguntaController->activar \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:josandro.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                    $this->flashMessenger()->addErrorMessage('PREGUNTA NO ACTUALIZADA DE JOSANDRO');
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
