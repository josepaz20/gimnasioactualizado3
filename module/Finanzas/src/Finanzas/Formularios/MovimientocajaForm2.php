<?php

namespace Finanzas\Formularios;

use Zend\Form\Form;

class MovimientocajaForm2 extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA VENTA DE PRODUCTO ? ")';
                $required = true;
                break;
            case 'editar':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS EDITADOS ?")';
                $required = true;
                break;
            case 'detalle':
                $onsubmit = '';
                $required = false;
                break;

            case 'eliminar':
                $onsubmit = 'return confirm("RECUERDE QUE ESTA VENTA DE PRODUCTO SERA ELIMINADA  ¿ DESEA ELIMINAR ESTA VENTA?")';
                $required = false;
                break;

            case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE LA VENTA DE PRODUCTO SERA ACTIVADA  ¿ DESEA ACTIVAR ESTA VENTA DE PRODUCTO?")';
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
            'name' => 'cliente',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
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
                'readonly' => !$required,
                'placeholder' => 'Digite el número de identificación del cliente',
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
                'required' => true,
                'readonly' => !$required,
                'placeholder' => 'Digite solo numeros',
                'id' => 'telefono',
            )
        ));

        $this->add(array(
            'name' => 'producto',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Proteina' => 'Proteina',
                    'Energizante' => 'Energizante',
                    'Saborizante' => 'Saborizante',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'onchange' => 'setTipoProducto(this.value)',
                'id' => 'producto',
            )
        ));
        $this->add(array(
            'name' => 'marca',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'IsoWell1' => 'IsoWell1',
                    'IsoWell2' => 'IsoWell2',
                    'IsoWell3' => 'IsoWell3',
                    'Energizante1' => 'Energizante1',
                    'Energizante2' => 'Energizante2',
                    'Energizante3' => 'Energizante3',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'marca',
            )
        ));
        
       
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'readonly' => !$required,
                'placeholder' => 'Digite un correo electrónico',
                'id' => 'email',
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
            'name' => 'costo',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    '10000' => 'IsoWell1(10000)',
                    '20000' => 'IsoWell2(20000)',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'costo',
            )
        ));



        $this->add(array(
            'name' => 'ingreso',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite el valor a pagar',
                'id' => 'ingreso',
            )
        ));
        
         $this->add(array(
            'name' => 'devolucion',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite el valor a pagar',
                'id' => 'devolucion',
            )
        ));
        $this->add(array(
            'name' => 'pendiente',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 11,
                'required' => true,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite el valor que se debe.',
                'id' => 'pendiente',
            )
        ));

        $this->add(array(
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechahorareg',
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
                'id' => 'tipo',
            )
        ));
    }

}
