<?php

namespace Mikrotik\Modelo\API;

require_once 'routeros_api.class.php';

class ApiMikroTik {

    private $API = null;
    private $debug = false;
    private $ipMikrotik;
    private $usuario;
    private $password;
    private $conectado = false;

//------------------------------------------------------------------------------    

    public function __construct($mikrotik = '') {
        $this->API = new RouterosAPI();
        $this->API->debug = $this->debug;
        switch ($mikrotik) {
            case 0:
                $this->ipMikrotik = '0.0.0.0';
                $this->usuario = 'asdfg';
                $this->password = '123456';
                break;
            case 1:
                $this->ipMikrotik = '190.90.60.113';
                $this->usuario = 'alpa';
                $this->password = 'Alpavision01';
                break;
//            case 1:
//                $this->ipMikrotik = '190.90.60.113';
//                $this->usuario = 'alpa';
//                $this->password = 'Alpavision01';
//                break;
            default:
                $this->API = null;
                break;
        }
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function exchangeArray($data) {
        $metodos = get_class_methods($this);
        foreach ($data as $key => $value) {
            $metodo = 'set' . ucfirst($key);
            if (in_array($metodo, $metodos)) {
                $this->$metodo($value);
            }
        }
    }

//------------------------------------------------------------------------------    

    public function conectar() {
        if ($this->API != null) {
            $this->conectado = $this->API->connect($this->ipMikrotik, $this->usuario, $this->password);
        }
        return $this->conectado;
    }

    public function desconectar() {
        if ($this->conectado) {
            $this->API->disconnect();
            $this->conectado = false;
        }
        return !$this->conectado;
    }

//------------------------------------------------------------------------------    

    public function getRegistrosAdrressList($lista = '') {
        $this->API->write('/ip/firewall/address-list/print', false);
        $this->API->write("?=list=" . trim($lista), true);
        $registros = $this->API->read();
        return $registros;
    }

    public function getRegistrosQueues() {
        $this->API->write('/queue/simple/print', true);
        $registros = $this->API->read();
        return $registros;
    }

    public function getRegistrosQueuesPorIP($ip = '') {
        $registros = array();
        if ($ip != '') {
            $this->API->write('/queue/simple/print', false);
            $this->API->write('?=target=' . $ip . '/32', true);
            $registros = $this->API->read();
        }
        return $registros;
    }

    public function getRegistroPorID($lista = '', $id = '') {
        $registro = array();
        switch ($lista) {
            case 'activo':
                $cmd = '/ip/firewall/address-list/print';
                break;
            case 'corte':
                $cmd = '/ip/firewall/address-list/print';
                break;
            case 'queues':
                $cmd = '/queue/simple/print';
                break;
            default:
                $cmd = '';
                break;
        }
        if ($cmd != '' && trim($id) != '') {
            $this->API->write($cmd, false);
            $this->API->write("?=.id=" . trim($id), true);
            $ips = $this->API->read();
            if (count($ips) > 0) {
                $registro = $ips[0];
            }
        }
        return $registro;
    }

    public function getRegistroAdrressListPorIP($lista = '', $ip = '') {
        $registro = array();
        switch ($lista) {
            case 'activo':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            case 'corte':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            default:
                $ips = array();
                break;
        }
        if (count($ips) > 0) {
            $registro = $ips[0];
        }
        return $registro;
    }

    public function getIdAddressListPorIP($lista = '', $ip = '') {
        $id = 0;
        switch ($lista) {
            case 'activo':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            case 'corte':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            default:
                $ips = array();
                break;
        }
        if (count($ips) > 0) {
            $id = $ips[0]['.id'];
        }
        return $id;
    }

//------------------------------------------------------------------------------
    public function verificarIP($lista = '', $ip = '', $mascara = '') {
        switch ($lista) {
            case 'activo':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            case 'corte':
                $this->API->write('/ip/firewall/address-list/print', false);
                $this->API->write('?=address=' . $ip, false);
                $this->API->write('?=list=' . $lista, false);
                $this->API->write('?#&', true);
                $ips = $this->API->read();
                break;
            case 'queues':
                $this->API->write('/queue/simple/print', false);
                $this->API->write('?=target=' . $ip . '/32', true);
                $ips = $this->API->read();
                break;
            case 'publica':
                $this->API->write('/ip/address/print', false);
                $this->API->write('?=address=' . $ip . '/' . $mascara, true);
                $ips = $this->API->read();
                break;
            default:
                $ips = array();
                break;
        }
        return count($ips);
    }

//------------------------------------------------------------------------------
    public function add($lista = '', $datos = array()) {
        switch (trim($lista)) {
            case 'activo':
                $cmd = '/ip/firewall/address-list/add';
                break;
            case 'corte':
                $cmd = '/ip/firewall/address-list/add';
                break;
            case 'queues':
                $cmd = '/queue/simple/add';
                break;
            default:
                $cmd = '';
                break;
        }
        if (count($datos) > 0 && $cmd != '') {
            $respuesta = $this->API->comm($cmd, $datos);
//            $respuesta = "DEPURACION";
            if (is_array($respuesta)) {
                return 'ERROR';
            } else {
                return $respuesta;
            }
        } else {
            return 'ERROR';
        }
    }

//------------------------------------------------------------------------------
    public function removePorID($lista = '', $id = '') {
        switch ($lista) {
            case 'activo':
                $cmd = '/ip/firewall/address-list/remove';
                break;
            case 'corte':
                $cmd = '/ip/firewall/address-list/remove';
                break;
            case 'queues':
                $cmd = '/queue/simple/remove';
                break;
            default:
                $cmd = '';
                break;
        }
        if ($cmd != '' && trim($id) != '') {
            $this->API->write($cmd, false);
            $this->API->write("=.id=" . trim($id), true);
            $respuesta = $this->API->read();
            if (count($respuesta) == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function removeQueues($ip = '') {
        $ok = true;
        if ($ip != '') {
            $queues = $this->getRegistrosQueuesPorIP($ip);
            if (count($queues) > 0) {
                foreach ($queues as $queue) {
                    if (!$this->removePorID('queues', $queue['.id'])) {
                        $ok = false;
                    }
                }
            }
        } else {
            $ok = false;
        }
        return $ok;
    }

//------------------------------------------------------------------------------

    public function cambiarIP($lista = '', $id = '', $ip = '') {
        switch ($lista) {
            case 'activo':
                $cmd = '/ip/firewall/address-list/set';
                break;
            case 'corte':
                $cmd = '/ip/firewall/address-list/set';
                break;
            case 'queues':
                $cmd = '/queue/simple/set';
                break;
            default:
                $cmd = '';
                break;
        }
        $this->API->write($cmd, false);
        if ($lista == 'queues') {
            $this->API->write('=target=' . $ip, false);
        } else {
            $this->API->write('=address=' . $ip, false);
        }
        $this->API->write("=.id=" . trim($id), true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function cambiarAnchosBanda($id = '', $anchosBanda = '') {
        $this->API->write('/queue/simple/set', false);
        $this->API->write('=max-limit=' . $anchosBanda, false);
        $this->API->write('=burst-threshold=' . $anchosBanda, false);
        $this->API->write("=.id=" . trim($id), true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function asignarIPpublica($infoIpAddress = array(), $infoNatDst = array(), $infoNatSrc = array(), $infoActivo = array(), $infoCorte = array(), $infoQueue = array()) {
        $cmd = '/ip/address/add';
        $respuesta = $this->API->comm($cmd, $infoIpAddress);
//        $this->imprimirDepuracion($cmd, $infoIpAddress);
//        return;
//        $respuesta = "DEPURACION";
        if (is_array($respuesta)) {
            return -1;
        } else {
            $cmd = '/ip/firewall/nat/add';
            $respuesta = $this->API->comm($cmd, $infoNatDst);
//            $this->imprimirDepuracion($cmd, $infoNatDst);
//            $respuesta = "DEPURACION";
            if (is_array($respuesta)) {
                return -2;
            } else {
                $cmd = '/ip/firewall/nat/add';
                $respuesta = $this->API->comm($cmd, $infoNatSrc);
//                $this->imprimirDepuracion($cmd, $infoNatSrc);
//                $respuesta = "DEPURACION";
                if (is_array($respuesta)) {
                    return -3;
                } else {
                    $cmd = '/queue/simple/add';
                    $respuesta = $this->API->comm($cmd, $infoQueue);
//                    $this->imprimirDepuracion($cmd, $infoQueue);
//                    $respuesta = "DEPURACION";
                    if (is_array($respuesta)) {
                        return -3;
                    } else {
                        $cmd = '/ip/firewall/address-list/add';
                        $respuesta = $this->API->comm($cmd, $infoActivo);
//                        $this->imprimirDepuracion($cmd, $infoActivo);
//                        $respuesta = "DEPURACION";
                        if (is_array($respuesta)) {
                            return -4;
                        } else {
                            if (count($infoCorte) > 0) {
                                $cmd = '/ip/firewall/address-list/add';
                                $respuesta = $this->API->comm($cmd, $infoCorte);
//                                $this->imprimirDepuracion($cmd, $infoCorte);
//                                $respuesta = "DEPURACION";
                                if (is_array($respuesta)) {
                                    return -5;
                                } else {
                                    return 1;
                                }
                            } else {
                                return 1;
                            }
                        }
                    }
                }
            }
        }
    }

//------------------------------------------------------------------------------

    public function imprimirDepuracion($cmd = '', $datos = array()) {
        echo '<br><br>' . $cmd . ' ';
        foreach ($datos as $campo => $vlr) {
            echo $campo . '=' . $vlr . ' ';
        }
        echo '<br>';
    }

//------------------------------------------------------------------------------

    public function getInterfaces() {
        $this->API->write('/interface/print', true);
        return $this->API->read();
    }

//------------------------------------------------------------------------------

    public function cambiarEstadoPorID($lista = '', $id = '', $estado = '') {
        switch ($lista) {
            case 'activo':
                $cmd = '/ip/firewall/address-list/' . $estado;
                break;
            case 'corte':
                $cmd = '/ip/firewall/address-list/' . $estado;
                break;
            case 'queues':
                $cmd = '/queue/simple/' . $estado;
                break;
            default:
                $cmd = '';
                break;
        }
        if ($cmd != '' && trim($id) != '') {
            $this->API->write($cmd, false);
            $this->API->write("=.id=" . trim($id), true);
            $respuesta = $this->API->read();
            if (count($respuesta) == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function enActivo($ip = '0.0.0.0') {
        $registro = $this->getRegistroAdrressListPorIP('activo', $ip);
        if (count($registro) > 0) {
            if ($registro['disabled'] == 'false') {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

//------------------------------------------------------------------------------

    public function enCorte($ip = '0.0.0.0') {
        $registro = $this->getRegistroAdrressListPorIP('corte', $ip);
        if (count($registro) > 0) {
            if ($registro['disabled'] == 'false') {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

//------------------------------------------------------------------------------

    public function cambiarIP_1($id = '', $ip = '') {
        $this->API->write('/ip/firewall/address-list/set', false);
        $this->API->write('=address=' . $ip, false);
        $this->API->write("=.id=" . trim($id), true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function getProfiles() {
        $this->API->write('/ppp/profile/print', true);
        $registros = $this->API->read();
        return $registros;
    }

    public function getRegistrosSecrets() {
        $this->API->write('/ppp/secret/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getSecret($id = 0) {
        $this->API->write('/ppp/secret/getall', false);
        $this->API->write('=.proplist=.id,name,password,profile', false);
        $this->API->write('?.id=' . $id);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function registrarSecret($datos = array()) {
        $respuesta = $this->API->comm('/ppp/secret/add', $datos);
//            $respuesta = "DEPURACION";
        if (is_array($respuesta)) {
            return 'ERROR';
        } else {
            return $respuesta;
        }
    }

//------------------------------------------------------------------------------

    public function editarSecret($id = '', $name = '', $password = '', $profile = '') {
        $this->API->write('/ppp/secret/set', false);
        $this->API->write('=.id=' . $id, false);
        $this->API->write('=name=' . $name, false);
        $this->API->write('=password=' . $password, false);
        $this->API->write('=profile=' . $profile, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function eliminarSecret($id = '') {
        $this->API->write('/ppp/secret/remove', false);
        $this->API->write('=.id=' . $id, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function getRegistrosPools() {
        $this->API->write('/ip/pool/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getPool($id = 0) {
        $this->API->write('/ip/pool/getall', false);
        $this->API->write('=.proplist=.id,name,ranges', false);
        $this->API->write('?.id=' . $id);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function registrarPool($datos = array()) {
        $respuesta = $this->API->comm('/ip/pool/add', $datos);
//            $respuesta = "DEPURACION";
        if (is_array($respuesta)) {
            return 'ERROR';
        } else {
            return $respuesta;
        }
    }

//------------------------------------------------------------------------------

    public function editarPool($id = '', $name = '', $ranges = '') {
        $this->API->write('/ip/pool/set', false);
        $this->API->write('=.id=' . $id, false);
        $this->API->write('=name=' . $name, false);
        $this->API->write('=ranges=' . $ranges, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function eliminarPool($id = '') {
        $this->API->write('/ip/pool/remove', false);
        $this->API->write('=.id=' . $id, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function getRegistrosQueuetypes() {
        $this->API->write('/queue/type/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getQueuetypes($id = 0) {
        $this->API->write('/queue/type/getall', false);
        $this->API->write('=.proplist=.id,name,ranges', false);
        $this->API->write('?.id=' . $id);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function registrarQueuetypes($datos = array()) {
        $respuesta = $this->API->comm('/queue/type/add', $datos);
//            $respuesta = "DEPURACION";
        if (is_array($respuesta)) {
            return 'ERROR';
        } else {
            return $respuesta;
        }
    }

//------------------------------------------------------------------------------

    public function editarQueuetypes($id = '', $name = '', $ranges = '') {
        $this->API->write('/ip/pool/set', false);
        $this->API->write('=.id=' . $id, false);
        $this->API->write('=name=' . $name, false);
        $this->API->write('=ranges=' . $ranges, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function eliminarQueuetypes($id = '') {
        $this->API->write('/ip/pool/remove', false);
        $this->API->write('=.id=' . $id, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function getRegistrosProfiles() {
        $this->API->write('/ppp/profile/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getProfile($id = 0) {
        $this->API->write('/ip/pool/getall', false);
        $this->API->write('=.proplist=.id,name,ranges', false);
        $this->API->write('?.id=' . $id);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function editarProfile($id = '', $name = '', $ranges = '') {
        $this->API->write('/ip/pool/set', false);
        $this->API->write('=.id=' . $id, false);
        $this->API->write('=name=' . $name, false);
        $this->API->write('=ranges=' . $ranges, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function eliminarProfile($id = '') {
        $this->API->write('/ip/pool/remove', false);
        $this->API->write('=.id=' . $id, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

    public function getAPI() {
        return $this->API;
    }

    public function getDebug() {
        return $this->debug;
    }

    public function getIpMikrotik() {
        return $this->ipMikrotik;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setAPI($api) {
        $this->API = $api;
    }

    public function setDebug($debug) {
        $this->debug = $debug;
        if ($this->API != null) {
            $this->API->debug = $this->debug;
        }
    }

    public function setIpMikrotik($ipMikrotik) {
        $this->ipMikrotik = $ipMikrotik;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

//------------------------------------------------------------------------------

    public function getSecretByUsername($username = '') {
        $this->API->write('/ppp/secret/getall', false);
        $this->API->write('=.proplist=.id,name,password,profile', false);
        $this->API->write('?name=' . $username);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function getConexionPPPoE($username = '') {
        $this->API->write('/ppp/active/getall', false);
        $this->API->write('=.proplist=.id,name', false);
        $this->API->write('?name=' . $username);
        $secret = $this->API->read();
        return $secret;
    }

//------------------------------------------------------------------------------

    public function eliminarConexionPPPoE($id = '') {
        $this->API->write('/ppp/active/remove', false);
        $this->API->write('=.id=' . $id, true);
        $respuesta = $this->API->read();
        if (count($respuesta) == 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function registrarProfile($datos = array()) {
        $respuesta = $this->API->comm('/ppp/profile/add', $datos);
//            $respuesta = "DEPURACION";
        if (is_array($respuesta)) {
            return 'ERROR';
        } else {
            return $respuesta;
        }
    }

//------------------------------------------------------------------------------

    public function getConexionesActivas() {
        $this->API->write('/ppp/active/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getLogs() {
        $this->API->write('/log/getall', true);
        $registros = $this->API->read();
        return $registros;
    }

//------------------------------------------------------------------------------

    public function getTraficoInterface($interface = '') {
        $this->API->write("/interface/monitor-traffic", false);
        $this->API->write("=interface=" . $interface, false);
        $this->API->write("=once=", true);
        $READ = $this->API->read(false);
        return $this->API->parseResponse($READ);
    }

//------------------------------------------------------------------------------

    public function getTraficoQueue($interface = '') {
        $this->API->write("/queue/simple/print", false);
        $this->API->write("=stats", false);
        $this->API->write("?name=" . $interface, true);
        $READ = $this->API->read(false);
        return $this->API->parseResponse($READ);
    }

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
}
