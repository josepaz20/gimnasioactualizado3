<?php

namespace Configuracion\Modelo\Entidades;

class CentroPoblado {

    private $pk_centro_poblado_id;
    private $fk_municipio_id;
    private $centropoblado;

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

    public function getPk_centro_poblado_id() {
        return $this->pk_centro_poblado_id;
    }

    public function getFk_municipio_id() {
        return $this->fk_municipio_id;
    }

    public function getCentropoblado() {
        return $this->centropoblado;
    }

    public function setPk_centro_poblado_id($pk_centro_poblado_id) {
        $this->pk_centro_poblado_id = $pk_centro_poblado_id;
    }

    public function setFk_municipio_id($fk_municipio_id) {
        $this->fk_municipio_id = $fk_municipio_id;
    }

    public function setCentropoblado($centropoblado) {
        $this->centropoblado = strtoupper($centropoblado);
    }

}
