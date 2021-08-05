<?php

namespace Configuracion\Modelo\Entidades;

class Departamento {

    private $idDepartamento;
    private $departamento;

//------------------------------------------------------------------------------

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

    public function getIdDepartamento() {
        return $this->idDepartamento;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setIdDepartamento($idDepartamento) {
        $this->idDepartamento = $idDepartamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

}
