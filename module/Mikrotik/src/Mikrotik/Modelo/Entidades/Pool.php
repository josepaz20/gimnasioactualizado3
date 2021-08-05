<?php

namespace Mikrotik\Modelo\Entidades;

class Pool {

    private $idPool;
    private $name;
    private $ranges;

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

    public function getIdPool() {
        return $this->idPool;
    }

    public function getName() {
        return $this->name;
    }

    public function getRanges() {
        return $this->ranges;
    }

    public function setIdPool($idPool) {
        $this->idPool = $idPool;
    }

    public function setName($name) {
        $this->name = strtoupper($name);
    }

    public function setRanges($ranges) {
        $this->ranges = $ranges;
    }

}
