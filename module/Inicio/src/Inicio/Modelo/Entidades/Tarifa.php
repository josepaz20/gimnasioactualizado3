<?php

namespace Contrataciontv\Modelo\Entidades;

class Tarifa {

    private $idTarifa;
    private $idSucursal;
    private $nombretarifa;
    private $valor;
    private $nummaxtvppal;
    private $nummaxtvadicional;
    private $fechaini;
    private $fechafin;
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

    public function getIdTarifa() {
        return $this->idTarifa;
    }

    public function getIdSucursal() {
        return $this->idSucursal;
    }

    public function getNombretarifa() {
        return $this->nombretarifa;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getNummaxtvppal() {
        return $this->nummaxtvppal;
    }

    public function getNummaxtvadicional() {
        return $this->nummaxtvadicional;
    }

    public function getFechaini() {
        return $this->fechaini;
    }

    public function getFechafin() {
        return $this->fechafin;
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

    public function setIdTarifa($idTarifa) {
        $this->idTarifa = $idTarifa;
    }

    public function setIdSucursal($idSucursal) {
        $this->idSucursal = $idSucursal;
    }

    public function setNombretarifa($nombretarifa) {
        $this->nombretarifa = strtoupper($nombretarifa);
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function setNummaxtvppal($nummaxtvppal) {
        $this->nummaxtvppal = $nummaxtvppal;
    }

    public function setNummaxtvadicional($nummaxtvadicional) {
        $this->nummaxtvadicional = $nummaxtvadicional;
    }

    public function setFechaini($fechaini) {
        $this->fechaini = $fechaini;
    }

    public function setFechafin($fechafin) {
        $this->fechafin = $fechafin;
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
