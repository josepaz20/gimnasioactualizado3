<?php

namespace Usuarios\Formularios;

use Zend\Form\Form;

class AccionForm extends Form {

    public function __construct($action = '') {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTA ACCION ?")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTA ACCION SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTA ACCION ?")';
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

        parent::__construct('formAccion');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'accion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'id' => 'accion',
            )
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'readonly' => !$required,
                'placeholder' => '',
                'maxlength' => 200,
                'id' => 'descripcion',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'pk_accion_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'pk_accion_id',
            )
        ));

        $this->add(array(
            'name' => 'estado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'estado',
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
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechahorareg',
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
            'name' => 'fechahoramod',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechahoramod',
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
                'value' => 'Guardar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
