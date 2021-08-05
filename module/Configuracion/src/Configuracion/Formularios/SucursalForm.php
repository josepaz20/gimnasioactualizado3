<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class SucursalForm extends Form {

    public function __construct($action = '') {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA SUCURSAL ? ")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTA SUCURSAL SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTA SUCURSAL ?")';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

//        $disabled = false;
//        if ($action == 'detail' || $action == 'delete') {
//            $disabled = true;
//        }

        parent::__construct('formSucursal');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);


        $this->add(array(
            'name' => 'sucursal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'style' => 'text-transform: uppercase',
                'maxlength' => 20,
                'readonly' => !$required,
                'required' => $required,
                'id' => 'sucursal',
            )
        ));

        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'style' => 'height: 100px; text-transform: uppercase',
                'maxlength' => 200,
                'readonly' => !$required,
                'id' => 'observacion',
            )
        ));

        $this->add(array(
            'name' => 'estado',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'Activo' => 'Activo',
                    'Desactivado' => 'Desactivado',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'estado',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'idSucursal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idSucursal',
            )
        ));

        $this->add(array(
            'name' => 'fk_centro_poblado_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fk_centro_poblado_id',
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
                'id' => 'fechaHoraReg',
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
