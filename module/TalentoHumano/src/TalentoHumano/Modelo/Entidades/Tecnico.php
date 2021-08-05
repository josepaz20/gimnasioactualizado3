<?php

namespace TalentoHumano\Modelo\Entidades;

class Tecnico {

    private $fk_empleado_id;
    private $nombresapellidos;
    private $fk_rol_id;

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

    public function getFk_empleado_id() {
        return $this->fk_empleado_id;
    }

    public function getNombresapellidos() {
        return $this->nombresapellidos;
    }

    public function getFk_rol_id() {
        return $this->fk_rol_id;
    }

    public function setFk_empleado_id($fk_empleado_id) {
        $this->fk_empleado_id = $fk_empleado_id;
    }

    public function setNombresapellidos($nombresapellidos) {
        $this->nombresapellidos = $nombresapellidos;
    }

    public function setFk_rol_id($fk_rol_id) {
        $this->fk_rol_id = $fk_rol_id;
    }

}
