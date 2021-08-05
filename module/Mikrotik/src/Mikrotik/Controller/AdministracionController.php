<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mikrotik\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Mikrotik\Formularios\QueueForm;
use Mikrotik\Formularios\SecretForm;
use Mikrotik\Formularios\PoolForm;
use Mikrotik\Modelo\API\ApiMikroTik;
use Mikrotik\Modelo\Procesos\ProcesosMikrotik;
use Mikrotik\Modelo\Entidades\Secret;
use Mikrotik\Modelo\Entidades\Pool;

class AdministracionController extends AbstractActionController {

    private $MIKROTIK = 1; // 1 --> Mikrotik Principal;   2 --> Mikrotik Depuracion
    private $abonadoDAO;

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

    public function getAbonadoDAO() {
        if (!$this->abonadoDAO) {
            $sm = $this->getServiceLocator();
            $this->abonadoDAO = $sm->get('Abonados\Modelo\DAO\AbonadoDAO');
        }
        return $this->abonadoDAO;
    }

//------------------------------------------------------------------------------    

    function getFormulario($action = '', $queueIP = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $form = new QueueForm($action);
        $registros = array();
        if ($queueIP != '') {
            if ($api->conectar()) {
                $registros = $api->getRegistrosQueuesPorIP($queueIP);
                if (count($registros) > 0) {
                    $ipArray = explode('/', $registros[0]['target']);
                    $registros[0]['target'] = $ipArray[0];
                    $form->setData($registros[0]);
                    $form->setData(array('idqueue' => $registros[0]['.id']));
                    if ($registros[0]['max-limit'] != '') {
                        $maxLimitArray = explode('/', $registros[0]['max-limit']);
                        $velsubida = $maxLimitArray[0] / 1024000;
                        $velbajada = $maxLimitArray[1] / 1024000;
                    } else {
                        $velsubida = 0;
                        $velbajada = 0;
                    }
                    $form->setData(array('velsubida' => round($velsubida, 3)));
                    $form->setData(array('velbajada' => round($velbajada, 3)));
                    $form->setData(array('consumosubida' => 0));
                    $form->setData(array('consumobajada' => 0));
                    return $form;
                } else {
                    return 1;
                }
                $api->desconectar();
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }

//------------------------------------------------------------------------------

    function getFormularioSecret($action = '', $id = '') {
        $secret = array();
        $profilesnames = array();
        if ($id != '') {
            $api = new ApiMikroTik($this->MIKROTIK);
            if ($api->conectar()) {
                $secret = $api->getSecret($id);
                $profiles = $api->getProfiles($id);
                foreach ($profiles as $profile) {
                    $profilesnames[] = $profile['name'];
                }
                $api->desconectar();
            }
        }
        $listaprofiles = array();
        foreach ($profilesnames as $profilename) {
//            print_r($profile);
            $listaprofiles[$profilename] = $profilename;
        }
        $form = new SecretForm($action, $listaprofiles);
        if (count($secret) != 0) {
            $secretOBJ = new Secret($secret[0]);
            $secretOBJ->setIdSecret($secret[0]['.id']);
            $form->bind($secretOBJ);
        }
        return $form;
    }

    function getFormularioPool($action = '', $id = '') {
        $pool = array();
        if ($id != '') {
            $api = new ApiMikroTik($this->MIKROTIK);
            if ($api->conectar()) {
                $pool = $api->getPool($id);
                $api->desconectar();
            }
        }
        $form = new PoolForm($action);
        if (count($pool) != 0) {
            $poolOBJ = new Pool($pool[0]);
            $poolOBJ->setIdPool($pool[0]['.id']);
            $form->bind($poolOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $lista = $this->params()->fromQuery('lista', 'activo');
        $registros = array();
//        $api->setDebug(true);
        if ($api->conectar()) {
            $registros = $api->getRegistrosAdrressList($lista);
        }
        return new ViewModel(array(
            'registros' => $registros,
            'lista' => $lista,
        ));
    }

//------------------------------------------------------------------------------

    public function queuesAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
            $registros = $api->getRegistrosQueues();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------

    public function detailQueueAction() {
        $action = 'detail';
        $queueIP = $this->params()->fromQuery('queueIP', '');
        $response = $this->getResponse();
        $form = $this->getFormulario($action, $queueIP);
        if (is_object($form)) {
            $view = new ViewModel(array(
                'form' => $form,
            ));
            $view->setTemplate('mikrotik/administracion/formulario');
            $view->setTerminal(true);
            return $view;
        } else {
            return $response->setContent(Json::encode($form));
        }
    }

//------------------------------------------------------------------------------

    public function getConsumoTiempoRealAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $response = $this->getResponse();
        $queueIP = $this->params()->fromQuery('queueIP', '');
        $consumosubida = 0;
        $consumobajada = 0;
        $error = 0;
        $registros = array();
        if ($queueIP != '') {
            $direccionIPArray = explode('/', $queueIP);
            $queueIP = $direccionIPArray[0];
        } else {
            $queueIP = '';
        }
        if ($api->conectar()) {
            $registros = $api->getRegistrosQueuesPorIP($queueIP);
            if (count($registros) > 0) {
                $consumoArray = explode('/', $registros[0]['rate']);
                $consumosubida = (int) $consumoArray[0];
                $consumobajada = (int) $consumoArray[1];
                if (($consumosubida / 1024000) <= 0) {
                    $consumosubida = 0;
                } else {
                    $consumosubida = $consumosubida / 1024000;
                }
                if (($consumobajada / 1024000) <= 0) {
                    $consumobajada = 0;
                } else {
                    $consumobajada = $consumobajada / 1024000;
                }
            } else {
                $error = 1;
            }
            $api->desconectar();
        } else {
            $error = 2;
        }
        return $response->setContent(Json::encode(array(
                            'consumosubida' => round($consumosubida, 3),
                            'consumobajada' => round($consumobajada, 3),
                            'error' => $error,
        )));
    }

//------------------------------------------------------------------------------

    public function habilitarAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $id = $this->params()->fromQuery('id', 0);
        $lista = $this->params()->fromQuery('lista', '');
        if ($api->conectar()) {
            if ($api->cambiarEstadoPorID($lista, $id, 'enable')) {
                $this->flashMessenger()->addSuccessMessage('CLIENTE HABILITADO.');
            } else {
                $this->flashMessenger()->addErrorMessage('NO SE PUDO HABILITAR EL CLIENTE.');
            }
            $api->desconectar();
        } else {
            $this->flashMessenger()->addErrorMessage('CONEXION NO ESTABLECIDA.');
        }
        return $this->redirect()->toUrl('../administracion/index?lista=' . $lista);
    }

//------------------------------------------------------------------------------

    public function deshabilitarAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $id = $this->params()->fromQuery('id', 0);
        $lista = $this->params()->fromQuery('lista', '');
        if ($api->conectar()) {
            if ($api->cambiarEstadoPorID($lista, $id, 'disable')) {
                $this->flashMessenger()->addSuccessMessage('CLIENTE DESABILITADO.');
            } else {
                $this->flashMessenger()->addErrorMessage('NO SE PUDO DESHABILITAR EL CLIENTE.');
            }
            $api->desconectar();
        } else {
            $this->flashMessenger()->addErrorMessage('CONEXION NO ESTABLECIDA.');
        }
        return $this->redirect()->toUrl('../administracion/index?lista=' . $lista);
    }

//------------------------------------------------------------------------------

    public function removerListaCorteAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $id = $this->params()->fromQuery('id', 0);
        $lista = $this->params()->fromQuery('lista', '');
        if ($api->conectar()) {
            if ($api->removePorID($lista, $id)) {
                $this->flashMessenger()->addSuccessMessage('CLIENTE REMOVIDO DE LA LISTA CORTE.');
            } else {
                $this->flashMessenger()->addErrorMessage('NO SE PUDO REMOVER EL CLIENTE.');
            }
            $api->desconectar();
        } else {
            $this->flashMessenger()->addErrorMessage('CONEXION NO ESTABLECIDA.');
        }
        return $this->redirect()->toUrl('../administracion/index?lista=' . $lista);
    }

//------------------------------------------------------------------------------

    public function addListaCorteAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $lista = $this->params()->fromQuery('lista', '');
        $ip = $this->params()->fromQuery('ip', '');
        $comment = $this->params()->fromQuery('comment', '');
        if ($api->conectar()) {
            $existeIP_corte = $api->verificarIP('corte', $ip);
            if ($existeIP_corte == 0) {
                $infoCorte = array(
                    "list" => 'corte',
                    "address" => $ip,
                    "comment" => $comment,
                    "disabled" => 'no'
                );
                if ($api->add($lista, $infoCorte) != 'ERROR') {
                    $this->flashMessenger()->addSuccessMessage('CLIENTE CORTADO.');
                } else {
                    $this->flashMessenger()->addErrorMessage('NO SE PUDO HACER EL CORTE.');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('ESTE CLIENTE YA SE ENCUENTRA EN CORTE.');
            }
            $api->desconectar();
        } else {
            $this->flashMessenger()->addErrorMessage('CONEXION NO ESTABLECIDA.');
        }
        return $this->redirect()->toUrl('../administracion/index?lista=' . $lista);
    }

//------------------------------------------------------------------------------    

    public function cargarmikrotikAction() {
        $mikrotik = new ProcesosMikrotik(1);
        $registros = $this->getAbonadoDAO()->getInfoInternetMKcarga();
        $error = 0;
        $msgLog = '';
        foreach ($registros as $registro) {
            if ($mikrotik->registrarIP($registro['ip'], $registro) != 1) {
                $msgLog .= "[" . date('Y-m-d H:i:s') . "] - IP: " . $registro['ip'] . " NO REGISTRADA EN MK. \n";
                $error++;
            }
        }
        $file = fopen("C:mikrotik.log", "a");
        fwrite($file, $msgLog);
        fclose($file);
        if ($error == 0) {
            $this->flashMessenger()->addSuccessMessage('REGISTROS CARGADOS A LA MIKROTIK');
        } else {
            $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE CARGAR LOS REGISTROS A LA MIKROTIK. Num. Errores: ' . $error);
        }
        return $this->redirect()->toUrl('../administracion/index?lista=activo');
    }

//------------------------------------------------------------------------------

    public function getgraficosAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $queueIP = $this->params()->fromQuery('queueIP', '');
        $consumosubida = 0;
        $consumobajada = 0;
        $error = 0;
        $registros = array();
        if ($queueIP != '') {
            $direccionIPArray = explode('/', $queueIP);
            $queueIP = $direccionIPArray[0];
        } else {
            $queueIP = '';
        }
        if ($api->conectar()) {
            $registros = $api->getRegistrosQueuesPorIP($queueIP);
            if (count($registros) > 0) {
                $consumoArray = explode('/', $registros[0]['rate']);
                $consumosubida = (int) $consumoArray[0];
                $consumobajada = (int) $consumoArray[1];
                if (($consumosubida / 1024000) <= 0) {
                    $consumosubida = 0;
                } else {
                    $consumosubida = $consumosubida / 1024000;
                }
                if (($consumobajada / 1024000) <= 0) {
                    $consumobajada = 0;
                } else {
                    $consumobajada = $consumobajada / 1024000;
                }
            } else {
                $error = 1;
            }
            $api->desconectar();
        } else {
            $error = 2;
        }
        return new ViewModel(array(
            'consumosubida' => round($consumosubida, 3),
            'consumobajada' => round($consumobajada, 3),
            'error' => $error,
            'queueIP' => $queueIP,
            'horaGetGrafico' => date('H:i:s'),
        ));
    }

//------------------------------------------------------------------------------

    public function getConsumoTiempoRealGraficoAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $response = $this->getResponse();
        $queueIP = $this->params()->fromQuery('queueIP', '');
        $consumosubida = 0;
        $consumobajada = 0;
        $error = 0;
        $registros = array();
        if ($queueIP != '') {
            $direccionIPArray = explode('/', $queueIP);
            $queueIP = $direccionIPArray[0];
        } else {
            $queueIP = '';
        }
        if ($api->conectar()) {
            $registros = $api->getRegistrosQueuesPorIP($queueIP);
            if (count($registros) > 0) {
                $consumoArray = explode('/', $registros[0]['rate']);
                $consumosubida = (int) $consumoArray[0];
                $consumobajada = (int) $consumoArray[1];
                if (($consumosubida / 1024000) <= 0) {
                    $consumosubida = 0;
                } else {
                    $consumosubida = $consumosubida / 1024000;
                }
                if (($consumobajada / 1024000) <= 0) {
                    $consumobajada = 0;
                } else {
                    $consumobajada = $consumobajada / 1024000;
                }
            } else {
                $error = 1;
            }
            $api->desconectar();
        } else {
            $error = 2;
        }

        return $response->setContent(Json::encode(array(
                            'consumosubida' => round($consumosubida, 3),
                            'consumobajada' => round($consumobajada, 3),
                            'error' => $error,
                            'horaConsumoTiempoReal' => date('H:i:s'),
        )));
    }

//------------------------------------------------------------------------------    

    public function secretsAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getRegistrosSecrets();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
//            'suspensiones' => $this->getAbonadoDAO()->setSuspendidos($registros),
        ));
    }

//------------------------------------------------------------------------------    

    public function editarsecretAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioSecret('editarsecret', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idSecret')->setValue($this->params()->fromPost('idSecret', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $secretOBJ = new Secret($form->getData());
                    if ($api->editarSecret($secretOBJ->getIdSecret(), $secretOBJ->getName(), $secretOBJ->getPassword(), $secretOBJ->getProfile())) {
                        $this->flashMessenger()->addSuccessMessage('EL SECRET FUE ACTUALIZADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL SECRET NO FUE ACTUALIZADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('secrets');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function eliminarsecretAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioSecret('eliminarsecret', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idSecret')->setValue($this->params()->fromPost('idSecret', ''));
            if ($form->isValid()) {
                $secretOBJ = new Secret($form->getData());
                if ($secretOBJ->getName() != '') {
                    $api = new ApiMikroTik($this->MIKROTIK);
                    if ($api->conectar()) {
                        if ($api->eliminarSecret($secretOBJ->getIdSecret())) {
                            $this->flashMessenger()->addSuccessMessage('EL SECRET FUE ELIMINADO EN LA MIKROTIK');
                        } else {
                            $this->flashMessenger()->addErrorMessage('EL SECRET NO FUE ELIMINADO EN LA MIKROTIK');
                        }
                        $api->desconectar();
                    } else {
                        $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA - Username vacio');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('secrets');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    
//------------------------------------------------------------------------------    

    public function poolsAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getRegistrosPools();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------    

    public function registrarpoolAction() {
        $form = $this->getFormularioPool('registrarpool');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    $datos = array(
                        'name' => $poolOBJ->getName(),
                        'ranges' => $poolOBJ->getRanges(),
                    );
                    if ($api->registrarPool($datos) != 'ERROR') {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE REGISTRADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE REGISTRADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editarpoolAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('editarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    if ($api->editarPool($poolOBJ->getIdPool(), $poolOBJ->getName(), $poolOBJ->getRanges())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ACTUALIZADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ACTUALIZADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function eliminarpoolAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('eliminarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $poolOBJ = new Pool($form->getData());
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    if ($api->eliminarPool($poolOBJ->getIdPool())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ELIMINADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ELIMINADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    
//------------------------------------------------------------------------------    

    public function profilesAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getRegistrosProfiles();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------    

    public function registrarprofileAction() {
        $form = $this->getFormularioPool('registrarpool');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    $datos = array(
                        'name' => $poolOBJ->getName(),
                        'ranges' => $poolOBJ->getRanges(),
                    );
                    if ($api->registrarPool($datos) != 'ERROR') {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE REGISTRADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE REGISTRADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editarprofileAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('editarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    if ($api->editarPool($poolOBJ->getIdPool(), $poolOBJ->getName(), $poolOBJ->getRanges())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ACTUALIZADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ACTUALIZADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function eliminarprofileAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('eliminarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $poolOBJ = new Pool($form->getData());
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    if ($api->eliminarPool($poolOBJ->getIdPool())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ELIMINADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ELIMINADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    
//------------------------------------------------------------------------------    

    public function queuetypesAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getRegistrosQueuetypes();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------    

    public function registrarqueuetypesAction() {
        $form = $this->getFormularioPool('registrarpool');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    $datos = array(
                        'name' => $poolOBJ->getName(),
                        'ranges' => $poolOBJ->getRanges(),
                    );
                    if ($api->registrarPool($datos) != 'ERROR') {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE REGISTRADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE REGISTRADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function editarqueuetypesAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('editarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    $poolOBJ = new Pool($form->getData());
                    if ($api->editarPool($poolOBJ->getIdPool(), $poolOBJ->getName(), $poolOBJ->getRanges())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ACTUALIZADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ACTUALIZADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function eliminarqueuetypesAction() {
        $id = $this->params()->fromQuery('id', '');
        $request = $this->getRequest();
        $form = $this->getFormularioPool('eliminarpool', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->get('idPool')->setValue($this->params()->fromPost('idPool', ''));
            if ($form->isValid()) {
                $poolOBJ = new Pool($form->getData());
                $api = new ApiMikroTik($this->MIKROTIK);
                if ($api->conectar()) {
                    if ($api->eliminarPool($poolOBJ->getIdPool())) {
                        $this->flashMessenger()->addSuccessMessage('EL POOL FUE ELIMINADO EN LA MIKROTIK');
                    } else {
                        $this->flashMessenger()->addErrorMessage('EL POOL NO FUE ELIMINADO EN LA MIKROTIK');
                    }
                    $api->desconectar();
                } else {
                    $this->flashMessenger()->addErrorMessage('NO FUE POSIBLE ESTABLECER CONEXION CON LA MIKROTIK');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('LA INFORMACION REGISTRADA NO ES VALIDA');
            }
            return $this->redirect()->toUrl('pools');
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function activasAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getConexionesActivas();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------    

    public function logsAction() {
        $api = new ApiMikroTik($this->MIKROTIK);
        $registros = array();
        if ($api->conectar()) {
//            $api->setDebug(true);
            $registros = $api->getLogs();
            $api->desconectar();
        }
        return new ViewModel(array(
            'registros' => $registros,
        ));
    }

//------------------------------------------------------------------------------    

    public function verinterfaceAction() {
        $username = $this->params()->fromQuery('username', '');
        $interface = "<pppoe-$username>";
        $view = new ViewModel(array(
            'interface' => $interface,
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    

    public function gettraficoAction() {
        $mikrotik = new ProcesosMikrotik(1);
        $interface = $this->params()->fromQuery('interface', '');
        $trafico = $mikrotik->getTrafico($interface);
        print json_encode($trafico, JSON_NUMERIC_CHECK);
        exit();
//        return new JsonModel(array(
//            $trafico
//        ), JSON_NUMERIC_CHECK);
    }

//------------------------------------------------------------------------------    
}
