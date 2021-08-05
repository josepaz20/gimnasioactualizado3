<?php

namespace Configuracion\Modelo\Entidades;

class Ubicacion {

    private $pk_departamento_id;
    private $pk_municipio_id;
    private $pk_centro_poblado_id;
    private $departamento;
    private $municipio;
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

    public function getPk_departamento_id() {
        return $this->pk_departamento_id;
    }

    public function getPk_municipio_id() {
        return $this->pk_municipio_id;
    }

    public function getPk_centro_poblado_id() {
        return $this->pk_centro_poblado_id;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function getCentropoblado() {
        return $this->centropoblado;
    }

    public function setPk_departamento_id($pk_departamento_id) {
        $this->pk_departamento_id = $pk_departamento_id;
    }

    public function setPk_municipio_id($pk_municipio_id) {
        $this->pk_municipio_id = $pk_municipio_id;
    }

    public function setPk_centro_poblado_id($pk_centro_poblado_id) {
        $this->pk_centro_poblado_id = $pk_centro_poblado_id;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    public function setCentropoblado($centropoblado) {
        $this->centropoblado = $centropoblado;
    }

}
