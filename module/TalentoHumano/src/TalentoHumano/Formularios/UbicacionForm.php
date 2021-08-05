<?php

namespace TalentoHumano\Formularios;

use Zend\Form\Form;

class UbicacionForm extends Form {

    public function __construct($departamentos = array(), $municipios = array(), $centrosPoblados = array()) {
        parent::__construct('formUbicacion');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', '');

        $this->add(array(
            'name' => 'idDepartamento',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $departamentos,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'getMunicipios(this.value)',
                'required' => true,
                'id' => 'idDepartamento',
            )
        ));
        $this->add(array(
            'name' => 'idMunicipio',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $municipios,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
//                'onchange' => 'getPoblados(this.value)',
                'required' => true,
                'id' => 'idMunicipio',
            )
        ));
        $this->add(array(
            'name' => 'pk_centro_poblado_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $centrosPoblados,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'setFkCentroPoblado(this.value)',
                'required' => true,
                'id' => 'pk_centro_poblado_id',
            )
        ));
    }

}
