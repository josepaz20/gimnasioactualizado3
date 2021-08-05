<?php

namespace Hotspot\Modelo\Entidades;

class Respuesta {

    private $idRespuesta;
    private $respuesta;
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
   
public function getIdRespuesta() {
        return $this->idRespuesta;
    }

    public function getRespuesta() {
        return $this->respuesta;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setIdRespuesta($idRespuesta) {
        $this->idRespuesta = $idRespuesta;
    }

    public function setRespuesta($respuesta) {
        $this->respuesta = $respuesta;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }
//------------------------------------------------------------------------------
}
