<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class BusquedasForm extends Form {

    public function __construct($departamentos = array(), $municipios = array(), $centrosPoblados = array()) {
        parent::__construct('formBusquedas');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', '');
        $this->setAttribute('onsubmit', '');

        $this->add(array(
            'name' => 'departamentoBusq',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $departamentos,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'getMunicipiosBusq(this.value)',
                'required' => true,
                'id' => 'departamentoBusq',
            )
        ));
        $this->add(array(
            'name' => 'municipioBusq',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $municipios,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'getCentroPobladoBusq(this.value)',
                'required' => true,
                'id' => 'municipioBusq',
            )
        ));
        $this->add(array(
            'name' => 'centroPobladoBusq',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $centrosPoblados,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => '',
                'required' => true,
                'id' => 'centroPobladoBusq',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'btnCancelar',
            'type' => 'Button',
            'options' => array(
                'label' => 'Cerrar',
            ),
            'attributes' => array(
                'value' => 'Cancelar',
                'class' => 'btn btn-primary',
                'data-dismiss' => 'modal',
                'id' => 'btnCancelar',
            ),
        ));

        $this->add(array(
            'name' => 'btnEnviar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
