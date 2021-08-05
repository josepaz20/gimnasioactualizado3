<?php

namespace Hotspot\Modelo\Entidades;

class Registro {

 private $idRegistro;

 private $producto;
 private $cantidad;
 private $fechahorareg;
 private $proveedor;
 private $ingreso;
 private $devolucion;
 private $pendiente;
 private $telefono;
  private $estado;
  
  public function getDevolucion() {
      return $this->devolucion;
  }

  public function setDevolucion($devolucion) {
      $this->devolucion = $devolucion;
  }

    
  public function getIdRegistro() {
      return $this->idRegistro;
  }

  public function getProducto() {
      return $this->producto;
  }

  public function getCantidad() {
      return $this->cantidad;
  }

  public function getFechahorareg() {
      return $this->fechahorareg;
  }

  public function getProveedor() {
      return $this->proveedor;
  }

  public function getIngreso() {
      return $this->ingreso;
  }

  public function getPendiente() {
      return $this->pendiente;
  }

  public function getTelefono() {
      return $this->telefono;
  }

  public function getEstado() {
      return $this->estado;
  }

  public function setIdRegistro($idRegistro) {
      $this->idRegistro = $idRegistro;
  }

  public function setProducto($producto) {
      $this->producto = $producto;
  }

  public function setCantidad($cantidad) {
      $this->cantidad = $cantidad;
  }

  public function setFechahorareg($fechahorareg) {
      $this->fechahorareg = $fechahorareg;
  }

  public function setProveedor($proveedor) {
      $this->proveedor = $proveedor;
  }

  public function setIngreso($ingreso) {
      $this->ingreso = $ingreso;
  }

  public function setPendiente($pendiente) {
      $this->pendiente = $pendiente;
  }

  public function setTelefono($telefono) {
      $this->telefono = $telefono;
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
 
    

 



//------------------------------------------------------------------------------


    
     


//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
}
