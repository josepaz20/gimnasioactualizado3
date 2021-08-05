<?php

namespace TalentoHumano\Modelo\Entidades;

class ContratoLaboral {

    private $idRegistro;
   
    private $empleado;
    private $identificacion;
    private $telefono;
    private $iniciocontrato;
    private $fincontrato;
    private $estado;
    
    
    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

        public function getIdRegistro() {
        return $this->idRegistro;
    }

    

    public function getEmpleado() {
        return $this->empleado;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getIniciocontrato() {
        return $this->iniciocontrato;
    }

    public function getFincontrato() {
        return $this->fincontrato;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setIdRegistro($idRegistro) {
        $this->idRegistro = $idRegistro;
    }

    

    public function setEmpleado($empleado) {
        $this->empleado = $empleado;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setIniciocontrato($iniciocontrato) {
        $this->iniciocontrato = $iniciocontrato;
    }

    public function setFincontrato($fincontrato) {
        $this->fincontrato = $fincontrato;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

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
}