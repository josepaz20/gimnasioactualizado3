<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class ZonaForm extends Form {

    public function __construct($action = '', $sucursales = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA ZONA ? ")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTA ZONA SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTA ZONA ?")';
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

        parent::__construct('formZona');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'idSucursal',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $sucursales,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'idSucursal',
            )
        ));

        $this->add(array(
            'name' => 'zona',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'style' => 'text-transform: uppercase',
                'maxlength' => 30,
                'readonly' => !$required,
                'required' => $required,
                'id' => 'zona',
            )
        ));

        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'style' => 'height: 100px; text-transform: uppercase',
                'maxlength' => 500,
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
                'id' => 'idTipoIncidente',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'pk_zona_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'pk_zona_id',
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
