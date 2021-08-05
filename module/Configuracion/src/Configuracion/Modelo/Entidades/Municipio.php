<?php

namespace Configuracion\Modelo\Entidades;

class Municipio {

    private $idMunicipio;
    private $municipio;

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

    public function getIdMunicipio() {
        return $this->idMunicipio;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setIdMunicipio($idMunicipio) {
        $this->idMunicipio = $idMunicipio;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

}
