<?php

namespace Hotspot\Formularios;

use Zend\Form\Form;

class ProductoForm extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTE PRODUCTO ? ")';
                $required = true;
                break;
            case 'editar':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $required = true;
                break;
            case 'detalle':
                $onsubmit = '';
                $required = false;
                break;

            case 'eliminar':
                $onsubmit = 'return confirm("RECUERDE QUE EL PRODUCTO SERA ELIMINADO  ¿ DESEA ELIMINAR ESTE PRODUCTO?")';
                $required = false;
                break;

            case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE EL PRODUCTO SERA ACTUALIZADO  ¿ DESEA ACTUALIZAR ESTE PRODUCTO?")';
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
            'name' => 'producto',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Energizante' => 'Energizante',
                    'Vitamina' => 'Vitamina',
                    'Proteina' => 'Proteina',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'disabled' => !$required,
                'id' => 'producto',
            )
        ));
          
        

        $this->add(array(
            'name' => 'cantidad',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 10,
                'required' => $required,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'cantidad',
            )
        ));

         $this->add(array(
            'name' => 'ingreso',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,             
                'placeholder' => 'Digite solo numeros',
                'id' => 'ingreso',
            )
        ));
         $this->add(array(
            'name' => 'devolucion',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,             
                'placeholder' => 'Digite solo numeros',
                'id' => 'devolucion',
            )
        ));
         
          $this->add(array(
            'name' => 'pendiente',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 10,
                'required' => $required,
                'readonly' => !$required,               
                'placeholder' => 'Digite solo numeros',
                'id' => 'pendiente',
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
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'readonly' => true,
                'readonly' => !$required,
                'id' => 'fechahorareg',
            )
        ));
        $this->add(array(
            'name' => 'proveedor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'readonly' => !$required,
                'id' => 'proveedor',
            )
        ));
           $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'maxlength' => 10,
                'required' => true,
                'readonly' => !$required,
                'pattern' => '[0-9]{1,20}',
                'placeholder' => 'Digite solo numeros',
                'id' => 'telefono',
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
                'id' => 'estado',
            )
        ));
    }

}
