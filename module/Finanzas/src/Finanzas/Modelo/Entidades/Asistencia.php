<?php

namespace Finanzas\Modelo\Entidades;

class Asistencia {

    private $idRegistro; 
    private $cliente;
    private $identificacion;
    private $telefono;
    private $entrenamiento;
    private $email;
    private $sexo;
    private $antecedentes;
    private $objetivo;
    private $estatura;
    private $valor;
    private $peso;
    private $pago;
    private $ingreso;
    private $devolucion;
    private $pendiente;
    private $comprobante;
    private $fechahorareg;
    private $fecharet;
    private $estado;
    
    
    public function getComprobante() {
        return $this->comprobante;
    }

    public function setComprobante($comprobante) {
        $this->comprobante = $comprobante;
    }

        
    public function getDevolucion() {
        return $this->devolucion;
    }

    public function setDevolucion($devolucion) {
        $this->devolucion = $devolucion;
    }

       
    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
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

    public function getEmail() {
        return $this->email;
    }

    public function getSexo() {
        return $this->sexo;
    }

   
    public function getAntecedentes() {
        return $this->antecedentes;
    }

    public function getObjetivo() {
        return $this->objetivo;
    }

    public function getEstatura() {
        return $this->estatura;
    }

    public function getPeso() {
        return $this->peso;
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
     public function getFecharet() {
        return $this->fecharet;
    }

    public function setFecharet($fecharet) {
        $this->fecharet = $fecharet;
    }

    public function getEntrenamiento() {
        return $this->entrenamiento;
    }

    public function setEntrenamiento($entrenamiento) {
        $this->entrenamiento = $entrenamiento;
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

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    

    public function setAntecedentes($antecedentes) {
        $this->antecedentes = $antecedentes;
    }

    public function setObjetivo($objetivo) {
        $this->objetivo = $objetivo;
    }

    public function setEstatura($estatura) {
        $this->estatura = $estatura;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
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
