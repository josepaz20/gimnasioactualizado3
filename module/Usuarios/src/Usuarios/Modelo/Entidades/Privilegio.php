<?php

namespace Usuarios\Modelo\Entidades;

class Privilegio {

    private $pk_privilegio_id;
    private $fk_recursoacl_id;
    private $fk_accion_id;
    private $fk_rol_id;
    private $permiso;
    private $observacion;
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

    public function getPk_privilegio_id() {
        return $this->pk_privilegio_id;
    }

    public function getFk_recursoacl_id() {
        return $this->fk_recursoacl_id;
    }

    public function getFk_accion_id() {
        return $this->fk_accion_id;
    }

    public function getFk_rol_id() {
        return $this->fk_rol_id;
    }

    public function getPermiso() {
        return $this->permiso;
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

    public function getFechahorareg() {
        return $this->fechahorareg;
    }

    public function getModificadopor() {
        return $this->modificadopor;
    }

    public function getFechahoramod() {
        return $this->fechahoramod;
    }

    public function setPk_privilegio_id($pk_privilegio_id) {
        $this->pk_privilegio_id = $pk_privilegio_id;
    }

    public function setFk_recursoacl_id($fk_recursoacl_id) {
        $this->fk_recursoacl_id = $fk_recursoacl_id;
    }

    public function setFk_accion_id($fk_accion_id) {
        $this->fk_accion_id = $fk_accion_id;
    }

    public function setFk_rol_id($fk_rol_id) {
        $this->fk_rol_id = $fk_rol_id;
    }

    public function setPermiso($permiso) {
        $this->permiso = $permiso;
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
