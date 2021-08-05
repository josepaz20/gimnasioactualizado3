<?php

namespace Mikrotik\Formularios;

use Zend\Form\Form;

class QueueForm extends Form {

    public function __construct($action = '') {
        switch ($action) {
            case 'add':
                $onsubmit = '';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

        parent::__construct('formQueue');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);


        $this->add(array(
            'name' => 'idqueue',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'idqueue',
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
            'name' => 'target',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'target',
            )
        ));

        $this->add(array(
            'name' => 'velsubida',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'velsubida',
            )
        ));

        $this->add(array(
            'name' => 'velbajada',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'velbajada',
            )
        ));
        
        $this->add(array(
            'name' => 'consumosubida',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'consumosubida',
            )
        ));

        $this->add(array(
            'name' => 'consumobajada',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'consumobajada',
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
