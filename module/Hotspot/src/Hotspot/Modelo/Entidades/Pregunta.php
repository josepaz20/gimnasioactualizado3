<?php

namespace Hotspot\Modelo\Entidades;

class Pregunta {

    private $idPregunta;
    private $pregunta;
    private $estado;

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
    public function getIdPregunta() {
        return $this->idPregunta;
    }

    public function getPregunta() {
        return $this->pregunta;
    }

    public function setIdPregunta($idPregunta) {
        $this->idPregunta = $idPregunta;
    }

    public function setPregunta($pregunta) {
        $this->pregunta = $pregunta;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

//------------------------------------------------------------------------------
}
