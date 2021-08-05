<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class TipoTarifaForm extends Form {

    public function __construct($action = '', $tiposServicio = array()) {
        switch ($action) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTE TIPO DE TARIFA ? ")';
                $required = true;
                break;
            case 'editar':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $required = true;
                break;
            case 'detalle':
                $onsubmit = '';
                $required = false;
                break;
            case 'eliminar':
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTE TIPO DE TARIFA SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTE TIPO DE TARIFA ?")';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

        parent::__construct('formTiposTarifa');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'idTipoServicio',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $tiposServicio,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'idTipoServicio',
            )
        ));

        $this->add(array(
            'name' => 'tipo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'maxlength' => 30,
                'style' => 'text-transform:uppercase',
                'placeholder' => 'Ej: INTERNET BANDA ANCHA 5 Mbps',
                'readonly' => !$required,
                'required' => $required,
                'id' => 'tipo',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'idTipoTarifa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idTipoTarifa',
            )
        ));

        $this->add(array(
            'name' => 'registradopor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'registradopor',
            )
        ));

        $this->add(array(
            'name' => 'modificadopor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'modificadopor',
            )
        ));

        $this->add(array(
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechahorareg',
            )
        ));

        $this->add(array(
            'name' => 'fechahoramod',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechahoramod',
            )
        ));
    }

}
