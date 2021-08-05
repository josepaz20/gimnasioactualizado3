<?php

namespace TalentoHumano\Formularios;

use Zend\Form\Form;

class EmpleadoForm extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTE EMPLEADO ? ")';
                $required = true;
                break;
            case 'editar':
                $onsubmit = 'return confirm("¿ DESEA EDITAR ESTE REGISTRO ?")';
                $required = true;
                break;
            case 'detalle':
                $onsubmit = '';
                $required = false;
                break;

            case 'eliminar':
                $onsubmit = 'return confirm("RECUERDE QUE EL EMPLEADO SERA ELIMINADO  ¿ DESEA ELIMINAR ESTE EMPLEADO?")';
                $required = false;
                break;

            case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE EL EMPLEADO SERA ACTIVADO  ¿ DESEA ACTUALIZAR ESTE EMPLEADO?")';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                break;
        }

//        !$required = false;
//        if ($accion == 'detail' || $accion == 'delete') {
//            !$required = true;
//        }

        parent::__construct('formHotspot');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $accion);
        $this->setAttribute('onsubmit', $onsubmit);

        

        $this->add(array(
            'name' => 'empleado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'readonly' => !$required,
                'placeholder'=>'Digite el nombre del empleado',
                'id' => 'empleado',
            )
        ));

         $this->add(array(
            'name' => 'identificacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 10,
                'required' => true,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite el número de identificación  ',
                'id' => 'identificacion',
            )
        ));
        $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 10,
                'maxlength' => 10,
                'required' => true,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'telefono',
            )
        ));
        $this->add(array(
            'name' => 'pago',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Total' => 'Total',
                    'Abono' => 'Abono',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'pago',
            )
        ));

        $this->add(array(
            'name' => 'ingreso',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'required' => true,
                 'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite el valor a pagar',
                'id' => 'ingreso',
                
            )
        ));
        $this->add(array(
            'name' => 'pendiente',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'required' => true,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'pendiente',
            )
        ));
       

        $this->add(array(
            'name' => 'estado',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Eliminado' => 'Eliminado',
                    'Activo' => 'Activo',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'pago',
            )
        ));





//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'idRegistro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'id' => 'idRegistro',
            )
        ));

        $this->add(array(
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => true,
                'readonly' => !$required,
                'id' => 'fechahorareg',
            )
        ));

       
    }

}
