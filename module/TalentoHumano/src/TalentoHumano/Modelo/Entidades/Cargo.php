<?php

namespace TalentoHumano\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Cargo implements InputFilterAwareInterface {

    private $pk_cargo_id;
    private $cargo;

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
    function getPk_cargo_id() {
        return $this->pk_cargo_id;
    }

    function getCargo() {
        return $this->cargo;
    }

    function setPk_cargo_id($pk_cargo_id) {
        $this->pk_cargo_id = $pk_cargo_id;
    }

    function setCargo($cargo) {
        $this->cargo = $cargo;
    }



  

}
