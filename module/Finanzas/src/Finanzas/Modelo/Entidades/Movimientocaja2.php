<?php

namespace Finanzas\Modelo\Entidades;

class Movimientocaja2 {

    private $idRegistro;
    
    private $cliente;
    private $identificacion;
    private $telefono;
    private $producto;
    private $marca;
    private $email;
    private $costo;
    private $pago;
    private $ingreso;
    private $devolucion;
    private $pendiente;
    private $fechahorareg;
    private $estado;

    
    public function getDevolucion() {
        return $this->devolucion;
    }

    public function setDevolucion($devolucion) {
        $this->devolucion = $devolucion;
    }

        public function getCosto() {
        return $this->costo;
    }

    public function setCosto($costo) {
        $this->costo = $costo;
    }

        

        
    public function getMarca() {
        return $this->marca;
    }

    public function setMarca($marca) {
        $this->marca = $marca;
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

    public function getIdRegistro() {
        return $this->idRegistro;
    }

    

    public function getCliente() {
        return $this->cliente;
    }

    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getProducto() {
        return $this->producto;
    }

    public function getEmail() {
        return $this->email;
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

   

    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setProducto($producto) {
        $this->producto = $producto;
    }

    public function setEmail($email) {
        $this->email = $email;
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

}
