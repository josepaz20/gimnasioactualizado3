<?php

namespace Usuarios\Modelo\Entidades;

class Usuario {

    private $pk_usuario_id;
    private $fk_empleado_id;
    private $fk_rol_id;
//    private $idSucursal;
    private $login;
    private $password;
    private $passwordseguro;
    private $nombresapellidos;
    private $sexo;
    private $estado;
    private $registradopor;
    private $fechahorareg;
    private $modificadopor;
    private $fechahoramod;

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

    public function getPk_usuario_id() {
        return $this->pk_usuario_id;
    }

    public function getFk_empleado_id() {
        return $this->fk_empleado_id;
    }

    public function getFk_rol_id() {
        return $this->fk_rol_id;
    }

//    public function getIdSurcursal() {
//        return $this->idSurcursal;
//    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPasswordseguro() {
        return $this->passwordseguro;
    }

    public function getNombresapellidos() {
        return $this->nombresapellidos;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getRegistradopor() {
        return $this->registradopor;
    }

    public function getFechahorareg() {
        return $this->fechahorareg;
    }

    public function getModificadopor() {
        return $this->modificadopor;
    }

    public function getFechahoramod() {
        return $this->fechahoramod;
    }

    public function setPk_usuario_id($pk_usuario_id) {
        $this->pk_usuario_id = $pk_usuario_id;
    }

    public function setFk_empleado_id($fk_empleado_id) {
        $this->fk_empleado_id = $fk_empleado_id;
    }

    public function setFk_rol_id($fk_rol_id) {
        $this->fk_rol_id = $fk_rol_id;
    }

//    public function setIdSucursal($idSucursal) {
//        $this->idSucursal = $idSucursal;
//    }

    public function setLogin($login) {
        $this->login = strtolower($login);
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPasswordseguro($passwordseguro) {
        $this->passwordseguro = $passwordseguro;
    }

    public function setNombresapellidos($nombresapellidos) {
        $this->nombresapellidos = strtoupper($nombresapellidos);
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setRegistradopor($registradopor) {
        $this->registradopor = $registradopor;
    }

    public function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    public function setModificadopor($modificadopor) {
        $this->modificadopor = $modificadopor;
    }

    public function setFechahoramod($fechahoramod) {
        $this->fechahoramod = $fechahoramod;
    }

}
