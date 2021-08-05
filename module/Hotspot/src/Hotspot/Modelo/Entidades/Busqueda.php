<?php

namespace Hotspot\Modelo\Entidades;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Busqueda implements InputFilterAwareInterface {

    private $identificacionFiltro;
    private $nombresFiltro;
    private $apellidosFiltro;
    private $razonsocialFiltro;
//------------------------------------------------------------------------------
    protected $inputFilter;

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
        if ($this->identificacionFiltro != '') {
            $filtro = "hotspot.identificacion like '%" . $this->identificacionFiltro . "%'";
        } elseif ($this->nombresFiltro != '' && $this->apellidosFiltro != '') {
            $filtro = "hotspot.nombres like '%" . $this->nombresFiltro . "%' AND hotspot.apellidos like '%" . $this->apellidosFiltro . "%'";
        } elseif ($this->getRazonsocialFiltro() != '') {
            $filtro = "hotspot.razonsocial like '%" . $this->razonsocialFiltro . "%'";
        } else {
            $filtro = 'hotspot.idHotspot = 0'; // 0 => error en el filtro de busqueda
        }
        return $filtro;
    }

//------------------------------------------------------------------------------

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

//------------------------------------------------------------------------------

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'identificacionFiltro',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'min' => 0,
                                    'max' => 20,
                                ),
                            ),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
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

//------------------------------------------------------------------------------
}
