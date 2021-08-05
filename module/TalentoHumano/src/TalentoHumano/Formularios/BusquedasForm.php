<?php

namespace TalentoHumano\Formularios;

use Zend\Form\Form;

class BusquedasForm extends Form {

    public function __construct($url = '', $sucursales = array()) {
        parent::__construct('formBusquedas');
        $this->setAttribute('method', 'get');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $url);
        $this->setAttribute('onsubmit', 'return validarBusqueda()');

        $this->add(array(
            'name' => 'idSucursalFiltro',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $sucursales,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'id' => 'idSucursalFiltro',
            )
        ));

        $this->add(array(
            'name' => 'identificacionFiltro',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'minlength' => 5,
                'maxlength' => 20,
                'id' => 'identificacionFiltro',
            )
        ));

        $this->add(array(
            'name' => 'nombresFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 30,
                'style' => 'text-transform:uppercase',
                'id' => 'nombresFiltro',
            )
        ));

        $this->add(array(
            'name' => 'apellidosFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 30,
                'style' => 'text-transform:uppercase',
                'id' => 'apellidosFiltro',
            )
        ));

        $this->add(array(
            'name' => 'razonsocialFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 50,
                'style' => 'text-transform:uppercase',
                'id' => 'razonsocialFiltro',
            )
        ));

//------------------------------------------------------------------------------
    }

}
