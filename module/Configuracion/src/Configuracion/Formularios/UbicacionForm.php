<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class UbicacionForm extends Form {

    public function __construct($action = 'detail', $departamentos = array(), $municipios = array(), $centrosPoblados = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE CENTRO POBLADO ?")';
                $required = true;
                break;
            case 'edit':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $required = true;
                break;
            case 'detail':
                $onsubmit = '';
                $required = false;
                break;
            case 'delete':
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTE CENTRO POBLADO SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTE CENTRO POBLADO ?")';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

        $disabled = false;
        if ($action == 'detail' || $action == 'delete') {
            $disabled = true;
        }

        parent::__construct('formUbicacion');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_departamento_id',
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
                'id' => 'pk_departamento_id',
            )
        ));
        $this->add(array(
            'name' => 'pk_municipio_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $municipios,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'onchange' => 'getPoblados(this.value)',
                'required' => true,
                'id' => 'pk_municipio_id',
            )
        ));
        if ($action == 'Buscar') {
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
        } else {
            $this->add(array(
                'name' => 'centropoblado',
                'attributes' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'required' => $required,
                    'readonly' => !$required,
                    'placeholder' => '',
                    'maxlength' => 80,
                    'style' => 'text-transform:uppercase',
                    'id' => 'centropoblado',
                )
            ));
        }

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'btnCancelar',
            'type' => 'Button',
            'options' => array(
                'label' => 'Cerrar',
            ),
            'attributes' => array(
                'value' => 'Cerrar',
                'class' => 'btn btn-danger',
                'data-dismiss' => 'modal',
                'id' => 'btnCancelar',
            ),
        ));

        $this->add(array(
            'name' => 'btnEnviar',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
