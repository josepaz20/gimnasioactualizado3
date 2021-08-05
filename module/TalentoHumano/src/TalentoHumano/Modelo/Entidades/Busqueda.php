<?php

namespace TalentoHumano\Modelo\Entidades;

class Busqueda {

    private $idSucursalFiltro;

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

    public function getFiltroBusqueda() {
        $filtro = '';
        if ($this->idSucursalFiltro != '') {
            if ($filtro == '') {
                $filtro = "empleado.idSucursal = " . $this->idSucursalFiltro;
            } else {
                $filtro .= " AND empleado.idSucursal = " . $this->idSucursalFiltro;
            }
        } else {
            $filtro = "empleado.idSucursal = 0";
        }
//        echo $filtro;
        return $filtro;
    }

//------------------------------------------------------------------------------

    public function getIdSucursalFiltro() {
        return $this->idSucursalFiltro;
    }

    public function setIdSucursalFiltro($idSucursalFiltro) {
        $this->idSucursalFiltro = $idSucursalFiltro;
    }

//------------------------------------------------------------------------------
}
