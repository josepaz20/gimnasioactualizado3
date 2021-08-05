<?php

namespace Mikrotik\Formularios;

use Zend\Form\Form;

class PoolForm extends Form {

    public function __construct($action = '') {
        switch ($action) {
            case 'registrarpool':
                $onsubmit = 'return validarRegistrarPool();';
                $required = true;
                break;
            case 'editarpool':
                $onsubmit = 'return validarEditarPool();';
                $required = true;
                break;
            case 'eliminarpool':
                $onsubmit = 'return validarEliminarPool();';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

        parent::__construct('formPool');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);


        $this->add(array(
            'name' => 'idPool',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'value' => '',
                'id' => 'idPool',
            )
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'name',
            )
        ));

        $this->add(array(
            'name' => 'ranges',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'value' => '',
                'id' => 'ranges',
            )
        ));
    }

}
