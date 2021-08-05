<?php

namespace Finanzas\Formularios;

use Zend\Form\Form;

class MovimientocajaForm extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTE CLIENTE ? ")';
                $required = false;
                break;
            case 'editar':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $required = false;
                break;
            case 'detalle':
                $onsubmit = '';
                $required = true;
                break;

            case 'eliminar':
                $onsubmit = 'return confirm("RECUERDE QUE EL CLIENTE SERA ELIMINADO  ¿ DESEA ELIMINAR ESTE CLIENTE?")';
                $required = true;
                break;

            case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE EL CLIENTE SERA ACTIVADO  ¿ DESEA ACTIVAR ESTE CLIENTE?")';
                $required = true;
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
            'name' => 'cliente',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => $required,
                'placeholder' => 'Digite el nombre del cliente',
                'id' => 'cliente',
            )
        ));

        $this->add(array(
            'name' => 'identificacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 10,
                'required' => $required,
                'readonly' => $required,
                'placeholder' => 'Digite el número de documento',
                'id' => 'identificacion',
            )
        ));
        $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 10,
                'required' => $required,
                'readonly' => $required,
                'placeholder' => 'Digite solo numeros',
                'id' => 'telefono',
            )
        ));
        $this->add(array(
            'name' => 'entrenamiento',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Mensual' => 'Mensual',
                    'Quincenal' => 'Quincenal',
                    'Diario' => 'Diario',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'onchange' => 'setTipoEntrenamiento(this.value)',
                'id' => 'entrenamiento',
            )
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => $required,
                'placeholder' => 'Digite un correo eléctronico',
                'id' => 'email',
            )
        ));

        $this->add(array(
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => $required,
                'id' => 'fechahorareg',
            )
        ));
        $this->add(array(
            'name' => 'fecharet',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => $required,
                'id' => 'fecharet',
            )
        ));
        $this->add(array(
            'name' => 'sexo',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Masculino' => 'Masculino',
                    'Femenino' => 'Femenino',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'id' => 'sexo',
            )
        ));

        $this->add(array(
            'name' => 'antecedentes',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Muscular' => 'Muscular',
                    'Osea' => 'Osea',
                    'Corazon' => 'Corazon',
                    'Respiratoria' => 'Respiratoria',
                    'CV' => 'CV',
                    'Ninguno' => 'Ninguno',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'id' => 'antecedentes',
            )
        ));

        $this->add(array(
            'name' => 'valor',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    '30000' => 'Mensual(30000)',
                    '15000' => 'Quincenal(15000)',
                    '3000' => 'Diario(3000)',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'id' => 'valor',
            )
        ));

        $this->add(array(
            'name' => 'pago',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Tarjeta' => 'Tarjeta',
                    'Efectivo' => 'Efectivo',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'id' => 'pago',
            )
        ));

        $this->add(array(
            'name' => 'ingreso',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 5,
                'required' => $required,
                'readonly' => $required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'ingreso',
            )
        ));
        
           $this->add(array(
            'name' => 'devolucion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 5,
                'required' => $required,
                'readonly' => $required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'devolucion',
            )
        ));
        $this->add(array(
            'name' => 'pendiente',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 5,
                'required' => $required,
                'readonly' => $required,
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
                'disabled' => $required,
                'id' => 'tipo',
            )
        ));

        $this->add(array(
            'name' => 'objetivo',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Aumentar' => 'Aumentar de Peso',
                    'Bajar' => 'Bajar de Peso',
                    'Mantener' => 'Mantener Peso',
                    'Rehabilitacion' => 'Rehabilitacion',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => $required,
                'id' => 'objetivo',
            )
        ));
        $this->add(array(
            'name' => 'estatura',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 3,
                'required' => $required,
                'readonly' => $required,
                'placeholder' => 'Digite solo numeros',
                'id' => 'estatura',
            )
        ));
        $this->add(array(
            'name' => 'peso',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 3,
                'required' => $required,
                'readonly' => $required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'peso',
            )
        ));
        
        $this->add(array(
            'name' => 'comprobante',
            'attributes' => array(
                'type' => 'file',
                'class' => 'form-control',                
                'id' => 'comprobante',
            )
        ));










//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'idRegistro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idRegistro',
            )
        ));

       
    }

}
