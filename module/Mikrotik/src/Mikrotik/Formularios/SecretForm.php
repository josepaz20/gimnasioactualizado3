<?php

namespace Mikrotik\Formularios;

use Zend\Form\Form;

class SecretForm extends Form {

    public function __construct($action = '', $profiles = array()) {
        switch ($action) {
            case 'editarsecret':
                $onsubmit = 'return validarEditarSecret();';
                $required = true;
                break;
            case 'eliminarsecret':
                $onsubmit = 'return validarEliminarSecret();';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

        parent::__construct('formSecret');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);


        $this->add(array(
            'name' => 'idSecret',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'value' => '',
                'id' => 'idSecret',
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'name',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'password',
            )
        ));

        $this->add(array(
            'name' => 'profile',
            'type' => 'select',
            'options' => array(
                'value_options' => $profiles,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'profile',
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
                'value' => 'Registrar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
