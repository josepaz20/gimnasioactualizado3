<?php

namespace Configuracion\Modelo\Entidades;

class Barrio {

    private $idBarrio;
    private $idZona;
    private $barrio;
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

    public function getIdBarrio() {
        return $this->idBarrio;
    }

    public function getIdZona() {
        return $this->idZona;
    }

    public function getBarrio() {
        return $this->barrio;
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

    public function setIdBarrio($idBarrio) {
        $this->idBarrio = $idBarrio;
    }

    public function setIdZona($idZona) {
        $this->idZona = $idZona;
    }

    public function setBarrio($barrio) {
        $this->barrio = $barrio;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setRegistradopor($registradopor) {
        $this->registradopor = $registradopor;
    }

    public function setModificadopor($modificadopor) {
        $this->modificadopor = $modificadopor;
    }

    public function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    public function setFechahoramod($fechahoramod) {
        $this->fechahoramod = $fechahoramod;
    }

}
