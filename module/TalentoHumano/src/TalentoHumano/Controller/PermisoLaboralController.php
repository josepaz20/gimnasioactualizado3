<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TalentoHumano\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use TalentoHumano\Formularios\PermisoLaboralForm;
use TalentoHumano\Modelo\Entidades\PermisoLaboral;

class PermisoLaboralController extends AbstractActionController {

    protected $permisoLaboralDAO;
    private $rutaArchivos = 'D:/UploadsPermisoLaboral/';

    public function getPermisoLaboralDAO() {
        if (!$this->permisoLaboralDAO) {
            $sm = $this->getServiceLocator();
            $this->permisoLaboralDAO = $sm->get('TalentoHumano\Modelo\DAO\PermisoLaboralDAO');
        }
        return $this->permisoLaboralDAO;
    }

//------------------------------------------------------------------------------
    function getFormulario($action = '', $idPermisoLaboral = 0) {
        $required = true;

        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }

        if ($sesionUsuario = $this->identity()) {
            $nombreUsuario = $sesionUsuario->nombresapellidos;
            $pk_usuario_id = $sesionUsuario->pk_usuario_id;
        }

        $form = new PermisoLaboralForm($action);
        $infoEmpleadoRegistro = $this->getPermisoLaboralDAO()->getInfoEmpleadoRegistro($pk_usuario_id);
        $form->setData($infoEmpleadoRegistro);

        if ($idPermisoLaboral != 0) {
            $PermisoLaboralOBJ = $this->getPermisoLaboralDAO()->getPermisoLaboral($idPermisoLaboral);
            $infoEmpleado = $this->getPermisoLaboralDAO()->getInfoEmpleado($PermisoLaboralOBJ->getFk_empleado_id());
            $form->bind($PermisoLaboralOBJ);
            $form->setData($infoEmpleado);
        }
        return $form;
    }

//------------------------------------------------------------------------------    

    public function solicitarPermisoAction() {

        if ($sesionUsuario = $this->identity()) {
            $nombreUsuario = $sesionUsuario->nombresapellidos;
            $pk_usuario_id = $sesionUsuario->pk_usuario_id;
        }
        $infoEmpleadoRegistro = $this->getPermisoLaboralDAO()->getInfoEmpleadoRegistro($pk_usuario_id);

        return new ViewModel(array(
            'permisos' => $this->getPermisoLaboralDAO()->getPermisosLaborales($infoEmpleadoRegistro['fk_empleado_id']),
        ));
    }

    public function confirmarPermisosAction() {
        return new ViewModel(array(
            'permisos' => $this->getPermisoLaboralDAO()->getPermisosLaborales(),
        ));
    }

    public function addAction() {
        $action = 'add';
        $form = $this->getFormulario($action);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $permisoLaboralOBJ = new PermisoLaboral($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }
                $permisoLaboralOBJ->setEstado('Registrado');
                $permisoLaboralOBJ->setRegistradopor($nombreUsuario);
                $permisoLaboralOBJ->setFechahorareg(date('Y-m-d H:i:s'));
                $permisoLaboralOBJ->setConfirmadopor('');
                $permisoLaboralOBJ->setFechahoraconfirm('0000-00-00 00:00:00');
                $permisoLaboralOBJ->setModificadopor('');
                $permisoLaboralOBJ->setFechahoramod('0000-00-00 00:00:00');

                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 5242880)); //1KB  
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('pdf', 'png', 'jpg', 'jpeg', 'rar')));
                $httpadapter->setValidators(array($filesize, $extension), $files['respaldo']['name']);
                if ($httpadapter->isValid()) {
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['respaldo']['name'], PATHINFO_EXTENSION);
                    $archivo = strtoupper(md5(rand() . $files['respaldo']['name'])) . '.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if ($httpadapter->receive($files['respaldo']['name'])) {
                        $permisoLaboralOBJ->setRespaldo($archivo);
                        $guardar = $this->getPermisoLaboralDAO()->guardar($permisoLaboralOBJ);
                        if ($guardar == 0) {
                            unlink($this->rutaArchivos . '/' . $archivo);
                        } else if ($guardar > 0) {
                            $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL REGISTRADO EN JOSANDRO');
                        } else {
                            $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE REGISTRADO EN JOSANDRO');
                        }
                    }
                } else {
                    $dataError = $httpadapter->getMessages();
                    $error = array();
                    foreach ($dataError as $key => $row) {
                        $error[] = $row;
                    }

                    $permisoLaboralOBJ->setRespaldo('');
                    $guardar = $this->getPermisoLaboralDAO()->guardar($permisoLaboralOBJ);
                    if ($guardar == 0) {
                        unlink($this->rutaArchivos . '/' . $archivo);
                    } else if ($guardar > 0) {
                        $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL REGISTRADO EN JOSANDRO');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE REGISTRADO EN JOSANDRO');
                    }
//                                    print_r($error);
                }
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'solicitar-permiso',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'solicitar-permiso',
                ));
            }
        }
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idPermisoLaboral = (int) $this->params()->fromQuery('idPermisoLaboral', 0);
        $action = 'edit';
        $form = $this->getFormulario($action, $idPermisoLaboral);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $permisoLaboralOBJ = new PermisoLaboral($form->getData());
                $nombreUsuario = '';
                if ($sesionUsuario = $this->identity()) {
                    $nombreUsuario = $sesionUsuario->nombresapellidos;
                }

                $permisoLaboralOBJ->setModificadopor($nombreUsuario);
                $permisoLaboralOBJ->setFechahoramod(date('Y-m-d H:i:s'));

                if ($this->getPermisoLaboralDAO()->actualizarPermisoLaboral($permisoLaboralOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL EDITADO SATISFACTORIAMENTE');
                } else {
                    $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE EDITADO');
                }
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'solicitar-permiso',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'solicitar-permiso',
                ));
            }
        }
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
            'refresh' => $this->params()->fromQuery('refresh', '')
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idPermisoLaboral = (int) $this->params()->fromQuery('idPermisoLaboral', 0);    
        $action = 'detail';
        $form = $this->getFormulario($action, $idPermisoLaboral);
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
            'refresh' => $this->params()->fromQuery('refresh', '')
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $idPermisoLaboral = (int) $this->params()->fromQuery('idPermisoLaboral', 0);

        $action = 'delete';
        $form = $this->getFormulario($action, $idPermisoLaboral);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $idPermisoLaboral = (int) $this->params()->fromPost('pk_permiso_id', 0);

            $permisoLaboralOBJ = new PermisoLaboral();
            $nombreEmpleado = '';
            if ($sesionEmpleado = $this->identity()) {
                $nombreEmpleado = $sesionEmpleado->nombresapellidos;
            }
            $permisoLaboralOBJ->setPk_permiso_id($idPermisoLaboral);
            $permisoLaboralOBJ->setEstado('Eliminado');
            $permisoLaboralOBJ->setModificadopor($nombreEmpleado);
            $permisoLaboralOBJ->setFechahoramod(date('Y-m-d H:i:s'));

            if ($this->getPermisoLaboralDAO()->eliminar($permisoLaboralOBJ) > 0) {
                $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL ELIMINADO SATISFACTORIAMENTE');
            } else {
                $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE ELIMINADO');
            }
            return $this->redirect()->toRoute('talentohumano/default', array(
                        'controller' => 'permiso-laboral',
                        'action' => 'solicitar-permiso',
            ));
        }
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
            'refresh' => $this->params()->fromQuery('refresh', '')
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function concederAction() {
        $idPermisoLaboral = (int) $this->params()->fromQuery('idPermisoLaboral', 0);

        $action = 'conceder';
        $form = $this->getFormulario($action, $idPermisoLaboral);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $idPermisoLaboral = (int) $this->params()->fromPost('pk_permiso_id', 0);

                $permisoLaboralOBJ = new PermisoLaboral($form->getData());
                $nombreEmpleado = '';
                if ($sesionEmpleado = $this->identity()) {
                    $nombreEmpleado = $sesionEmpleado->nombresapellidos;
                }
                $permisoLaboralOBJ->setPk_permiso_id($idPermisoLaboral);
                $permisoLaboralOBJ->setEstado('Concedido');
                $permisoLaboralOBJ->setConfirmadopor($nombreEmpleado);
                $permisoLaboralOBJ->setFechahoraconfirm(date('Y-m-d H:i:s'));

                if ($this->getPermisoLaboralDAO()->confirmarPermisoLaboral($permisoLaboralOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL CONCEDIDO SATISFACTORIAMENTE');
                } else {
                    $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE CONCEDIDO');
                }
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'confirmar-permisos',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'confirmar-permisos',
                ));
            }
        }
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
            'refresh' => $this->params()->fromQuery('refresh', '')
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function denegarAction() {
        $idPermisoLaboral = (int) $this->params()->fromQuery('idPermisoLaboral', 0);

        $action = 'denegar';
        $form = $this->getFormulario($action, $idPermisoLaboral);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $idPermisoLaboral = (int) $this->params()->fromPost('pk_permiso_id', 0);

                $permisoLaboralOBJ = new PermisoLaboral($form->getData());
                $nombreEmpleado = '';
                if ($sesionEmpleado = $this->identity()) {
                    $nombreEmpleado = $sesionEmpleado->nombresapellidos;
                }
                $permisoLaboralOBJ->setPk_permiso_id($idPermisoLaboral);
                $permisoLaboralOBJ->setEstado('Denegado');
                $permisoLaboralOBJ->setConfirmadopor($nombreEmpleado);
                $permisoLaboralOBJ->setFechahoraconfirm(date('Y-m-d H:i:s'));                                
                
                if ($this->getPermisoLaboralDAO()->confirmarPermisoLaboral($permisoLaboralOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('PERMISO LABORAL DENEGADO SATISFACTORIAMENTE');
                } else {
                    $this->flashMessenger()->addErrorMessage('EL PERMISO LABORAL NO FUE DENEGADO');
                }
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'confirmar-permisos',
                ));
            } else {
                return $this->redirect()->toRoute('talentohumano/default', array(
                            'controller' => 'permiso-laboral',
                            'action' => 'confirmar-permisos',
                ));
            }
        }
        $view = new ViewModel(array(
            'formPermisoLaboral' => $form,
            'refresh' => $this->params()->fromQuery('refresh', '')
        ));
        $view->setTemplate('talento-humano/permiso-laboral/formulario');
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------------------------------------------
    //DESCARGA DE ARCHIVOS (RESPALDO)

    public function descargarAction() {
        $idPermisoLaboral = (int) $this->params()->fromRoute('id1', 0);
        $refresh = $this->params()->fromRoute('id2', '');

        $respaldo = $this->getPermisoLaboralDAO()->getRespaldo($idPermisoLaboral);

        if ($respaldo == 'SIN RUTA') {
            $this->flashMessenger()->addErrorMessage('ARCHIVO ADJUNTO NO LOCALIZADO EN BASE DE DATOS.');
            return $this->redirect()->toRoute('talentohumano/default', array(
                        'controller' => 'permiso-laboral',
                        'action' => $refresh,
            ));
        }
        $ruta = $this->rutaArchivos . $respaldo;
        if (is_file($ruta)) {
            $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
            switch ($ext) {
                case 'pdf':
                    header('Content-Type: application/pdf');
                    break;
                case 'jpg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/jpeg');
                    break;
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'rar':
                    header("Content-type: application/octet-stream");
                    header("Content-disposition: attachment; filename=archivoRespaldo.rar");
                    break;
                default :
                    header("Content-type: application/octet-stream");
                    header("Content-disposition: attachment; filename=archivoRespaldo." . $ext);
                    break;
            }
            readfile($ruta);
        } else {
            $this->flashMessenger()->addErrorMessage('ARCHIVO ADJUNTO NO LOCALIZADO EN SERVIDOR.');
            return $this->redirect()->toRoute('talentohumano/default', array(
                        'controller' => 'permiso-laboral',
                        'action' => $refresh,
            ));
        }
    }

}
