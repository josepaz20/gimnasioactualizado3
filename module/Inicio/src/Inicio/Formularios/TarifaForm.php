<?php

namespace Contrataciontv\Formularios;

use Zend\Form\Form;

class TarifaForm extends Form {

    public function __construct($action = '', $sucursales = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA TARIFA DE TV ? ")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTA TARIFA DE TV SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTA TARIFA DE TV ?")';
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

        parent::__construct('formTarifastv');
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
            'name' => 'nombretarifa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Ej. Basico, Basico2, etc.',
                'maxlength' => 20,
                'style' => 'text-transform:uppercase',
                'readonly' => !$required,
                'required' => $required,
                'id' => 'nombretarifa',
            )
        ));

        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type' => 'text',
                'pattern' => '[0-9]{1,10}',
                'class' => 'form-control',
                'placeholder' => 'Ingrese solo numeros',
                'maxlength' => 10,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'valor',
            )
        ));

        $this->add(array(
            'name' => 'nummaxtvppal',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'max' => 100,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nummaxtvppal',
            )
        ));

        $this->add(array(
            'name' => 'nummaxtvadicional',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'max' => 100,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nummaxtvadicional',
            )
        ));

        $this->add(array(
            'name' => 'fechaini',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaini',
            )
        ));

        $this->add(array(
            'name' => 'fechafin',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechafin',
            )
        ));

        $this->add(array(
            'name' => 'estado',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'Registrado' => 'Registrado',
                    'Activo' => 'Activo',
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
            'name' => 'idTarifa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idTarifa',
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
