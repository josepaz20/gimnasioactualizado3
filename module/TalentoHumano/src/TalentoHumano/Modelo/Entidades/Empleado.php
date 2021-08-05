<?php

namespace TalentoHumano\Modelo\Entidades;

class Empleado {

    private $idRegistro;
   
    private $empleado;
    private $identificacion;
    private $telefono;
    private $pago;
    private $ingreso;
    private $pendiente;
    private $fechahorareg;
    private $estado;
    
    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

        public function getIdRegistro() {
        return $this->idRegistro;
    }

   

    public function getEmpleado() {
        return $this->empleado;
    }

    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function getPago() {
        return $this->pago;
    }

    public function getIngreso() {
        return $this->ingreso;
    }

    public function getPendiente() {
        return $this->pendiente;
    }

    public function getFechahorareg() {
        return $this->fechahorareg;
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

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    public function setPago($pago) {
        $this->pago = $pago;
    }

    public function setIngreso($ingreso) {
        $this->ingreso = $ingreso;
    }

    public function setPendiente($pendiente) {
        $this->pendiente = $pendiente;
    }

    public function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

//------------------------------------------------------------------------------
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

}

//------------------------------------------------------------------------------

    
