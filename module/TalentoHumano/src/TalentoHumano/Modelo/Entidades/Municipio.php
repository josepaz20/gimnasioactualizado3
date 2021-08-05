<?php

namespace TalentoHumano\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Municipio implements InputFilterAwareInterface {

    private $idMunicipio;
    private $idDepartamento;
    private $municipio;

    public function __construct(array $datos = null) {
        if (is_array($datos)) {
            $this->exchangeArray($datos);
        }
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function getInputFilter() {
        
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

    public function getIdDepartamento() {
        return $this->idDepartamento;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function setIdMunicipio($idMunicipio) {
        $this->idMunicipio = $idMunicipio;
    }

    public function setIdDepartamento($idDepartamento) {
        $this->idDepartamento = $idDepartamento;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

}
