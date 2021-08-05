<?php

namespace Configuracion\Modelo\Entidades;

class Sucursal {

    private $idSucursal;
    private $fk_centro_poblado_id;
    private $sucursal;
    private $observacion;
    private $estado;
    private $registradopor;
    private $modificadopor;
    private $fechahorareg;
    private $fechahoramod;

    public function __construct(array $datos = null) {
        $this->fechahoramod = '0000-00-00 00:00:00';
        if (is_array($datos)) {
            $this->exchangeArray($datos);
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

    public function getIdSucursal() {
        return $this->idSucursal;
    }

    public function getFk_centro_poblado_id() {
        return $this->fk_centro_poblado_id;
    }

    public function getSucursal() {
        return $this->sucursal;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getRegistradopor() {
        return $this->registradopor;
    }

    public function getModificadopor() {
        return $this->modificadopor;
    }

    public function getFechahorareg() {
        return $this->fechahorareg;
    }

    public function getFechahoramod() {
        return $this->fechahoramod;
    }

    public function setIdSucursal($idSucursal) {
        $this->idSucursal = $idSucursal;
    }

    public function setFk_centro_poblado_id($fk_centro_poblado_id) {
        $this->fk_centro_poblado_id = $fk_centro_poblado_id;
    }

    public function setSucursal($sucursal) {
        $this->sucursal = strtoupper($sucursal);
    }

    public function setObservacion($observacion) {
        $this->observacion = strtoupper($observacion);
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setRegistradopor($registradopor) {
        $this->registradopor = substr($registradopor, 0, 20);
    }

    public function setModificadopor($modificadopor) {
        $this->modificadopor = substr($modificadopor, 0, 20);
    }

    public function setFechahorareg($fechahorareg) {
        if ($fechahorareg == '') {
            $this->fechahorareg = '0000-00-00 00:00:00';
        } else {
            $this->fechahorareg = $fechahorareg;
        }
    }

    public function setFechahoramod($fechahoramod) {
        if ($fechahoramod == '') {
            $this->fechahoramod = '0000-00-00 00:00:00';
        } else {
            $this->fechahoramod = $fechahoramod;
        }
    }

}
