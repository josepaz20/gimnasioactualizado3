<?php

namespace Usuarios\Modelo\Entidades;

class Accion {

    private $pk_accion_id;
    private $accion;
    private $descripcion;
    private $estado;
    private $registradopor;
    private $fechahorareg;
    private $modificadopor;
    private $fechahoramod;

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

    public function getPk_accion_id() {
        return $this->pk_accion_id;
    }

    public function getAccion() {
        return $this->accion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getRegistradopor() {
        return $this->registradopor;
    }

    public function getFechahorareg() {
        return $this->fechahorareg;
    }

    public function getModificadopor() {
        return $this->modificadopor;
    }

    public function getFechahoramod() {
        return $this->fechahoramod;
    }

    public function setPk_accion_id($pk_accion_id) {
        $this->pk_accion_id = $pk_accion_id;
    }

    public function setAccion($accion) {
        $this->accion = $accion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setRegistradopor($registradopor) {
        $this->registradopor = $registradopor;
    }

    public function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    public function setModificadopor($modificadopor) {
        $this->modificadopor = $modificadopor;
    }

    public function setFechahoramod($fechahoramod) {
        $this->fechahoramod = $fechahoramod;
    }

}
