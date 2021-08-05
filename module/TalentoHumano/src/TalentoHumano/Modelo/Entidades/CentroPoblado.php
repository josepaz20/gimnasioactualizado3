<?php

namespace TalentoHumano\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class CentroPoblado implements InputFilterAwareInterface {

    private $pk_centro_poblado_id;
    private $fk_municipio_id;
    private $centropoblado;

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
    
    function getPk_centro_poblado_id() {
        return $this->pk_centro_poblado_id;
    }

    function getFk_municipio_id() {
        return $this->fk_municipio_id;
    }

    function getCentropoblado() {
        return $this->centropoblado;
    }

    function setPk_centro_poblado_id($pk_centro_poblado_id) {
        $this->pk_centro_poblado_id = $pk_centro_poblado_id;
    }

    function setFk_municipio_id($fk_municipio_id) {
        $this->fk_municipio_id = $fk_municipio_id;
    }

    function setCentropoblado($centropoblado) {
        $this->centropoblado = $centropoblado;
    }





}
