<?php

namespace Mikrotik\Modelo\Procesos;

use Mikrotik\Modelo\API\ApiMikroTik;

class ProcesosMikrotik {

    private $MIKROTIK = 1; // 1 --> Mikrotik Principal;   2 --> Mikrotik Depuracion

//------------------------------------------------------------------------------

    public function __construct($mikrotik = 0) {
        $this->MIKROTIK = $mikrotik;
    }

//------------------------------------------------------------------------------    

    public function limpiarCadena($string) {
        if (is_string($string)) {
            $string = trim($string);
            $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
            $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
            $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
            $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
            $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
            $string = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string);
        }
        return $string;
    }

//------------------------------------------------------------------------------

    public function registrarIP($ip = '0.0.0.0', $infoInternet = array()) {
        $api = new ApiMikroTik($this->MIKROTIK);
        if ($api->conectar()) {
            $existeIP_activo = $api->verificarIP('activo', $ip);
            $existeIP_corte = $api->verificarIP('corte', $ip);
            $existeIP_queues = $api->verificarIP('queues', $ip);
            if ($existeIP_activo == 0 && $existeIP_corte == 0 && $existeIP_queues == 0) {
                $idServicio = $infoInternet['idInternet'];
                $cliente = substr($this->limpiarCadena($infoInternet['cliente']), 0, 50);
                $anchosBanda = $infoInternet['velsubida'] . 'k/' . $infoInternet['velbajada'] . 'k';
                $comentario = strtoupper("$idServicio;$cliente");
                $infoActivo = array(
                    "list" => 'activo',
                    "address" => $ip,
                    "comment" => $comentario,
                    "disabled" => 'no'
                );
                if ($api->add('activo', $infoActivo) != 'ERROR') {
                    $cliente = "$idServicio;$cliente";
                    $infoQueue = array(
                        "name" => strtoupper($cliente),
                        "target" => $ip,
                        "max-limit" => $anchosBanda,
                        "burst-threshold" => $anchosBanda
                    );
                    if ($api->add('queues', $infoQueue) != 'ERROR') {
                        return 1;
                    } else {
                        return 2; // 'ERROR_KODOS_QUEUE';
                    }
                } else {
                    return 3; // 'ERROR_KODOS_ACTIVO';
                }
            } else {
                return 4; // 'ERROR_IP_DUPLICADA';
            }
            $api->desconectar();
        } else {
            return 5; // 'ERROR_CONEXION_KODOS';
        }
    }

//------------------------------------------------------------------------------    

    public function estaServicioActivo($ip = '0.0.0.0') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $respuesta = 3; // ERROR AL VERIFICAR LA MIKROTIK
        if ($api->conectar()) {
            $enActivo = $api->enActivo($ip);
            $enCorte = $api->enCorte($ip);
            $api->desconectar();
            if ($enActivo == 1 && $enCorte == 0) {
                $respuesta = 1;
            } else {
                if ($enActivo == 0) {
                    $respuesta = 2;
                } else {
                    $respuesta = 0;
                }
            }
        }
        return $respuesta;
    }

//------------------------------------------------------------------------------    

    public function quitarCorteServicio($ip = '0.0.0.0') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $ok = false;
        if ($api->conectar()) {
            $lista = 'corte';
            $id = $api->getIdAddressListPorIP($lista, $ip);
            $ok = $api->removePorID($lista, $id);
            $api->desconectar();
        }
        return $ok;
    }

//------------------------------------------------------------------------------    

    public function cortarServicio($ip = '0.0.0.0', $comentario = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $ok = false;
        if ($api->conectar()) {
            $info = array(
                "list" => 'corte',
                "address" => $ip,
                "comment" => $comentario,
                "disabled" => 'no'
            );
//            print_r($info);
            if ($api->add('corte', $info) != 'ERROR') {
                $ok = true;
            }
            $api->desconectar();
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function cambiarIP($ipOLD = '', $ipNEW = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $okActivo = 0;
        $okCorte = 1;
        $okQueues = 0;
        if ($api->conectar()) {
            $existeIP_activo = $api->verificarIP('activo', $ipNEW);
            $existeIP_corte = $api->verificarIP('corte', $ipNEW);
            $existeIP_queues = $api->verificarIP('queues', $ipNEW);
            if ($existeIP_activo == 0 && $existeIP_corte == 0 && $existeIP_queues == 0) {
                $registroActivo = $api->getRegistroAdrressListPorIP('activo', $ipOLD);
                if (count($registroActivo) > 0) {
                    if ($api->cambiarIP('activo', $registroActivo['.id'], $ipNEW)) {
                        $okActivo = 1;
                    }
                }
                $registroCorte = $api->getRegistroAdrressListPorIP('corte', $ipOLD);
                if (count($registroCorte) > 0) {
                    if (!$api->cambiarIP('corte', $registroCorte['.id'], $ipNEW)) {
                        $okCorte = 0;
                    }
                }
                $queues = $api->getRegistrosQueuesPorIP($ipOLD);
                foreach ($queues as $queue) {
                    if ($api->cambiarIP('queues', $queue['.id'], $ipNEW)) {
                        $okQueues = 1;
                    } else {
                        $okQueues = 0;
                    }
                }
                if ($okActivo == 1 && $okCorte == 1 && $okQueues == 1) {
                    return 1;
                } else {
                    if ($okActivo == 0) {
                        return 2;
                    }
                    if ($okCorte == 0) {
                        return 3;
                    }
                    if ($okQueues == 0) {
                        return 4;
                    }
                }
            } else {
                return 5; // 'ERROR_IP_DUPLICADA';
            }
            $api->desconectar();
        } else {
            return 6; // 'ERROR_CONEXION_KODOS';
        }
    }

//------------------------------------------------------------------------------

    public function cambiarTarifa($ip = '', $anchosBanda = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $ok = 1;
        if ($api->conectar()) {
            $queues = $api->getRegistrosQueuesPorIP($ip);
            foreach ($queues as $queue) {
                if (!$api->cambiarAnchosBanda($queue['.id'], $anchosBanda)) {
                    $ok = 0;
                }
            }
            $api->desconectar();
        } else {
            return 2; // 'ERROR_CONEXION_KODOS';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function eliminarServicio($ip = '0.0.0.0') {
        $api = new ApiMikroTik($this->MIKROTIK);
        $error = 0;
        if ($api->conectar()) {
            $registroActivo = $api->getRegistroAdrressListPorIP('activo', $ip);
            if (count($registroActivo) > 0) {
                if (!$api->removePorID('activo', $registroActivo['.id'])) {
                    $error = 1;
                }
            }

            $registroCorte = $api->getRegistroAdrressListPorIP('activo', $ip);
            if (count($registroCorte) > 0) {
                if (!$api->removePorID('corte', $registroCorte['.id'])) {
                    $error = 2;
                }
            }
            $queues = $api->getRegistrosQueuesPorIP($ip);
            foreach ($queues as $queue) {
                if (!$api->removePorID('queues', $queue['.id'])) {
                    $error = 3;
                }
            }
            $api->desconectar();
        } else {
            return 4; // 'ERROR_CONEXION_KODOS';
        }
        return $error;
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function cortarServicioProfile($cortes = array()) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $ok = true;
        $msg = 'NO';
        if ($api->conectar()) {
            foreach ($cortes as $corte) {
                $secret = $api->getSecretByUsername($corte['username']);
//                print_r($secret);
                if (count($secret) > 0) {
                    $id = $secret[0]['.id'];
                    $name = $secret[0]['name'];
                    $password = $secret[0]['password'];
                    $profile = 'corte';
                    if ($api->editarSecret($id, $name, $password, $profile)) {
                        $conexionpppoe = $api->getConexionPPPoE($corte['username']);
                        if (count($conexionpppoe) > 0) {
                            $id = $conexionpppoe[0]['.id'];
                            $api->eliminarConexionPPPoE($id);
                        }
                    } else {
                        $ok = false;
                    }
                } else {
                    $msg = 'no secret';
                }
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function cortarServicioByUsername($username = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $ok = 1;
        $msg = 'NO';
        if ($api->conectar()) {
            $secret = $api->getSecretByUsername($username);
//                print_r($secret);
            if (count($secret) > 0) {
                $id = $secret[0]['.id'];
                $name = $secret[0]['name'];
                $password = $secret[0]['password'];
                $profile = 'corte';
                if ($api->editarSecret($id, $name, $password, $profile)) {
                    $conexionpppoe = $api->getConexionPPPoE($username);
                    if (count($conexionpppoe) > 0) {
                        $id = $conexionpppoe[0]['.id'];
                        $api->eliminarConexionPPPoE($id);
                    }
                } else {
                    $ok = 0;
                }
            } else {
                $msg = 'no secret';
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function activarServicioProfile($username = '', $profile = 6) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $msg = '';
        $ok = false;
        if ($api->conectar()) {
            $secret = $api->getSecretByUsername($username);
//            print_r($secret);
            if (count($secret) > 0) {
                $id = $secret[0]['.id'];
                $name = $secret[0]['name'];
                $password = $secret[0]['password'];
                $profile = $profile;
                if ($api->editarSecret($id, $name, $password, $profile)) {
                    $ok = true;
                    $conexionpppoe = $api->getConexionPPPoE($username);
                    if (count($conexionpppoe) > 0) {
                        $id = $conexionpppoe[0]['.id'];
                        $api->eliminarConexionPPPoE($id);
                    }
                }
            } else {
                $msg = 'no secret';
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function exportarProfiles($tarifas = array()) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $msg = '';
        $ok = true;
        if ($api->conectar()) {
            $i = 1;
            foreach ($tarifas as $tarifa) {
                $datos = array(
                    'name' => $tarifa->getIdTarifa(),
                    'local-address' => '10.0.0.' . $i,
                    'remote-address' => 'pool1',
                    'dns-server' => '8.8.8.8,1.1.1.1',
                    'address-list' => 'activo',
                    'rate-limit' => $tarifa->getVelsubida() . 'm/' . $tarifa->getVelbajada() . 'm',
                );
                $i++;
                if ($api->registrarProfile($datos) == 'ERROR') {
                    $ok = false;
                }
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function exportarSecrets($abonados = array()) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $msg = '';
        $ok = true;
        if ($api->conectar()) {
            foreach ($abonados as $abonado) {
                $secret = $api->getSecretByUsername($abonado->getUsername());
                if (count($secret) > 0) {
                    $id = $secret[0]['.id'];
                    $name = $secret[0]['name'];
                    $password = $secret[0]['password'];
                    $profile = $abonado->getIdTarifa();
                    if ($api->editarSecret($id, $name, $password, $profile)) {
                        $ok = true;
                        $conexionpppoe = $api->getConexionPPPoE($abonado->getUsername());
                        if (count($conexionpppoe) > 0) {
                            $id = $conexionpppoe[0]['.id'];
                            $api->eliminarConexionPPPoE($id);
                        }
                    }
                } else {
                    $datos = array(
                        'name' => $abonado->getUsername(),
                        'password' => $abonado->getPassword(),
                        'profile' => $abonado->getIdTarifa(),
                        'service' => 'pppoe',
                    );
                    if ($api->registrarSecret($datos)) {
                        $ok = true;
                    }
                }
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

    public function registrarSecret($username = '', $password = '', $profile = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $ok = 0;
        if ($api->conectar()) {
            $secret = $api->getSecretByUsername($username);
            if (count($secret) > 0) {
                return 2; // USERNAME YA EXISTE
            } else {
                $datos = array(
                    'name' => $username,
                    'password' => $password,
                    'profile' => $profile,
                    'service' => 'pppoe',
                );
                if ($api->registrarSecret($datos)) {
                    $ok = 1;
                }
                $api->desconectar();
            }
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function cambiarTarifaProfile($username = '', $profile = 6) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $msg = '';
        $ok = false;
        if ($api->conectar()) {
            $secret = $api->getSecretByUsername($username);
//            print_r($secret);
            if (count($secret) > 0) {
                $id = $secret[0]['.id'];
                $name = $secret[0]['name'];
                $password = $secret[0]['password'];
                $profile = $profile;
                if ($api->editarSecret($id, $name, $password, $profile)) {
                    $ok = true;
                    $conexionpppoe = $api->getConexionPPPoE($username);
                    if (count($conexionpppoe) > 0) {
                        $id = $conexionpppoe[0]['.id'];
                        $api->eliminarConexionPPPoE($id);
                    }
                }
            } else {
                $msg = 'no secret';
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function patearpppoeByUsername($username = '') {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        $ok = 1;
        $msg = 'NO';
        if ($api->conectar()) {
            $conexionpppoe = $api->getConexionPPPoE($username);
            if (count($conexionpppoe) > 0) {
                $id = $conexionpppoe[0]['.id'];
                $api->eliminarConexionPPPoE($id);
            }
            $api->desconectar();
        } else {
            $msg = 'no conexion';
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function getTrafico($interface = '', $tipo = 0) {
        $api = new ApiMikroTik($this->MIKROTIK);
//        $api->setDebug(true);
        if ($api->conectar()) {
            $rows = array();
            $rows2 = array();
            if ($tipo == 0) {  // Interfaces
                $ARRAY = $api->getTraficoInterface($interface);
                if (count($ARRAY) > 0) {
                    $rx = ($ARRAY[0]["rx-bits-per-second"]);
                    $tx = ($ARRAY[0]["tx-bits-per-second"]);
                    $rows['name'] = 'Tx';
                    $rows['data'][] = $tx;
                    $rows2['name'] = 'Rx';
                    $rows2['data'][] = $rx;
                } else {
                    return $ARRAY['!trap'][0]['message'];
                }
            } else if ($tipo == 1) { //  Queues
                $ARRAY = $api->getTraficoQueue($interface);
                if (count($ARRAY) > 0) {
                    $rx = explode("/", $ARRAY[0]["rate"])[0];
                    $tx = explode("/", $ARRAY[0]["rate"])[1];
                    $rows['name'] = 'Tx';
                    $rows['data'][] = $tx;
                    $rows2['name'] = 'Rx';
                    $rows2['data'][] = $rx;
                } else {
                    return $ARRAY['!trap'][0]['message'];
                }
            }
            $api->desconectar();
            $result = array();
            array_push($result, $rows);
            array_push($result, $rows2);
            return $result;
        } else {
            return "<font color='#ff0000'>La conexion ha fallado. Verifique si el Api esta activo.</font>";
        }
    }

//------------------------------------------------------------------------------
}
