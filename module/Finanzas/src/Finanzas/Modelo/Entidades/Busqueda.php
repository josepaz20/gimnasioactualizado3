<?php

namespace Finanzas\Modelo\Entidades;

class Busqueda {

    private $identificacionFiltro;
    private $nombresFiltro;
    private $apellidosFiltro;
    private $razonsocialFiltro;
    private $referenciapagoFiltro;
    private $direccionFiltro;

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
        $filtro = "";
        if ($this->identificacionFiltro != '') {
            $filtro = "cliente.identificacion = '" . $this->identificacionFiltro . "'";
        }
        if ($this->nombresFiltro != '') {
            if ($filtro != "") {
                $filtro .= " AND ";
            }
            $filtro .= "cliente.nombres like '%" . $this->nombresFiltro . "%'";
        }
        if ($this->apellidosFiltro != '') {
            if ($filtro != "") {
                $filtro .= " AND ";
            }
            $filtro .= "cliente.apellidos like '%" . $this->apellidosFiltro . "%'";
        }
        if ($this->razonsocialFiltro != '') {
            if ($filtro != "") {
                $filtro .= " AND ";
            }
            $filtro .= "cliente.razonsocial like '%" . $this->apellidosFiltro . "%'";
        }
        if ($this->referenciapagoFiltro != '') {
            if ($filtro != "") {
                $filtro .= " AND ";
            }
            $filtro .= "servicio.referenciapago = " . $this->referenciapagoFiltro;
        }
        if ($this->direccionFiltro != '') {
            if ($filtro != "") {
                $filtro .= " AND ";
            }
            $filtro .= "direcciones.direccion like '%" . $this->direccionFiltro . "%'";
        }
        return $filtro;
    }

//------------------------------------------------------------------------------

    public function getIdentificacionFiltro() {
        return $this->identificacionFiltro;
    }

    public function getNombresFiltro() {
        return $this->nombresFiltro;
    }

    public function getApellidosFiltro() {
        return $this->apellidosFiltro;
    }

    public function getRazonsocialFiltro() {
        return $this->razonsocialFiltro;
    }

    public function getReferenciapagoFiltro() {
        return $this->referenciapagoFiltro;
    }

    public function getDireccionFiltro() {
        return $this->direccionFiltro;
    }

    public function setIdentificacionFiltro($identificacionFiltro) {
        $this->identificacionFiltro = $identificacionFiltro;
    }

    public function setNombresFiltro($nombresFiltro) {
        $this->nombresFiltro = $nombresFiltro;
    }

    public function setApellidosFiltro($apellidosFiltro) {
        $this->apellidosFiltro = $apellidosFiltro;
    }

    public function setRazonsocialFiltro($razonsocialFiltro) {
        $this->razonsocialFiltro = $razonsocialFiltro;
    }

    public function setReferenciapagoFiltro($referenciapagoFiltro) {
        $this->referenciapagoFiltro = $referenciapagoFiltro;
    }

    public function setDireccionFiltro($direccionFiltro) {
        $this->direccionFiltro = $direccionFiltro;
    }

//------------------------------------------------------------------------------
}
