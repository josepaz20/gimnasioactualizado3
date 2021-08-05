<?php

namespace TalentoHumano\Modelo\Entidades;

class PermisoLaboral{

    private $pk_permiso_id;
    private $fk_empleado_id;
    private $motivo;
    private $fechapermiso;
    private $dias;
    private $horas;
    private $descripcion;
    private $respaldo;
    private $estado;
    private $observacion;
    private $registradopor;
    private $fechahorareg;
    private $modificadopor;
    private $fechahoramod;
    private $confirmadopor;
    private $fechahoraconfirm;
    
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

    public function getPk_permiso_id() {
        return $this->pk_permiso_id;
    }

    public function getFk_empleado_id() {
        return $this->fk_empleado_id;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function getFechapermiso() {
        return $this->fechapermiso;
    }

    public function getDias() {
        return $this->dias;
    }

    public function getHoras() {
        return $this->horas;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getRespaldo() {
        return $this->respaldo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getObservacion() {
        return $this->observacion;
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

    public function getConfirmadopor() {
        return $this->confirmadopor;
    }

    public function getFechahoraconfirm() {
        return $this->fechahoraconfirm;
    }

    public function setPk_permiso_id($pk_permiso_id) {
        $this->pk_permiso_id = $pk_permiso_id;
    }

    public function setFk_empleado_id($fk_empleado_id) {
        $this->fk_empleado_id = $fk_empleado_id;
    }

    public function setMotivo($motivo) {
        $this->motivo = strtoupper($motivo);
    }

    public function setFechapermiso($fechapermiso) {
        $this->fechapermiso = $fechapermiso;
    }

    public function setDias($dias) {
        $this->dias = $dias;
    }

    public function setHoras($horas) {
        $this->horas = $horas;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setRespaldo($respaldo) {
        $this->respaldo = $respaldo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
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

    public function setConfirmadopor($confirmadopor) {
        $this->confirmadopor = $confirmadopor;
    }

    public function setFechahoraconfirm($fechahoraconfirm) {
        $this->fechahoraconfirm = $fechahoraconfirm;
    }


}
