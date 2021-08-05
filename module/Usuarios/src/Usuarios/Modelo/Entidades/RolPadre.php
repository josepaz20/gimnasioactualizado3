<?php

namespace Usuarios\Modelo\Entidades;

class RolPadre {

    private $fk_rol_id;
    private $fk_rol_padre_id;
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

    public function getFk_rol_id() {
        return $this->fk_rol_id;
    }

    public function getFk_rol_padre_id() {
        return $this->fk_rol_padre_id;
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

    public function setFk_rol_id($fk_rol_id) {
        $this->fk_rol_id = $fk_rol_id;
    }

    public function setFk_rol_padre_id($fk_rol_padre_id) {
        $this->fk_rol_padre_id = $fk_rol_padre_id;
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
