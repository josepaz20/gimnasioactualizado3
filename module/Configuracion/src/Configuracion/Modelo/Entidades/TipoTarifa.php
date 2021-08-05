<?php

namespace Configuracion\Modelo\Entidades;

class TipoTarifa {

    private $idTipoTarifa;
    private $idTipoServicio;
    private $tipo;
    private $registradopor;
    private $modificadopor;
    private $fechahorareg;
    private $fechahoramod;
//------------------------------------------------------------------------------
    private $tiposervicio;

    public function __construct(array $datos = null) {
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

    public function getIdTipoTarifa() {
        return $this->idTipoTarifa;
    }

    public function getIdTipoServicio() {
        return $this->idTipoServicio;
    }

    public function getTipo() {
        return $this->tipo;
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

    public function setIdTipoTarifa($idTipoTarifa) {
        $this->idTipoTarifa = $idTipoTarifa;
    }

    public function setIdTipoServicio($idTipoServicio) {
        $this->idTipoServicio = $idTipoServicio;
    }

    public function setTipo($tipo) {
        $this->tipo = strtoupper($tipo);
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

//------------------------------------------------------------------------------
    public function getTiposervicio() {
        return $this->tiposervicio;
    }

    public function setTiposervicio($tiposervicio) {
        $this->tiposervicio = $tiposervicio;
    }

}
