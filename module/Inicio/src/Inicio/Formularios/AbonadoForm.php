<?php

namespace Contrataciontv\Formularios;

use Zend\Form\Form;

class AbonadoForm extends Form {

    public function __construct($action = '', $sucursales = array(), $zonas = array(), $barrios = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return validarAdd()';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTE ABONADO DE TV SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTE ABONADO DE TV ?")';
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

        parent::__construct('formAbonadotv');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'tipocliente',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Empresa' => 'Empresa',
                    'Persona' => 'Persona',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'tipocliente',
            )
        ));

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
            'name' => 'fk_zona_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ..',
                'value_options' => $zonas,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => $required,
                'class' => 'form-control',
                'onchange' => 'getBarrios(this.value)',
                'id' => 'fk_zona_id',
            )
        ));

        $this->add(array(
            'name' => 'fk_barrio_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ..',
                'value_options' => $barrios,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => $required,
                'class' => 'form-control',
                'id' => 'fk_barrio_id',
            )
        ));

        $this->add(array(
            'name' => 'conceptofacturacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'conceptofacturacion',
            )
        ));

        $this->add(array(
            'name' => 'tarifa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'tarifa',
            )
        ));

        $this->add(array(
            'name' => 'numtvsprincipal',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'min' => 1,
                'max' => 100,
                'id' => 'numtvsprincipal',
            )
        ));

        $this->add(array(
            'name' => 'numtvsadicionales',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'min' => 0,
                'max' => 100,
                'id' => 'numtvsadicionales',
            )
        ));

        $this->add(array(
            'name' => 'instalargratis',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    '1' => 'SI',
                    '0' => 'NO',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'onchange' => 'setInstalarGratis(this.value)',
                'id' => 'instalargratis',
            )
        ));

        $this->add(array(
            'name' => 'pagoinstalacion',
            'attributes' => array(
                'type' => 'text',
                'pattern' => '[0-9]{1,10}',
                'class' => 'form-control',
                'placeholder' => 'Ingrese solo numeros',
                'maxlength' => 10,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'pagoinstalacion',
            )
        ));

        $this->add(array(
            'name' => 'dirinstalacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'placeholder' => 'Direccion de residencia',
                'maxlength' => 100,
                'style' => 'text-transform:uppercase',
                'id' => 'dirinstalacion',
            )
        ));

        $this->add(array(
            'name' => 'diacorte',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    '15' => '15',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'id' => 'diacorte',
            )
        ));

        $this->add(array(
            'name' => 'modalidadpago',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'Factura Instalacion' => 'Factura Instalacion',
                    'Primera Factura Servicio' => 'Primera Factura Servicio',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'modalidadpago',
            )
        ));

        $this->add(array(
            'name' => 'facturaren',
            'type' => 'select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'Independiente' => 'Independiente',
                    'Misma Factura' => 'Misma Factura',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'facturaren',
            )
        ));

        $this->add(array(
            'name' => 'diasinstalacion',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'min' => 1,
                'max' => 30,
                'id' => 'diasinstalacion',
            )
        ));

        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'style' => 'height: 100px; text-transform: uppercase',
                'maxlength' => 300,
                'readonly' => !$required,
                'id' => 'observacion',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'idServicioTV',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idServicioTV',
            )
        ));

        $this->add(array(
            'name' => 'fk_empresa_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fk_empresa_id',
            )
        ));

        $this->add(array(
            'name' => 'fk_persona_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fk_persona_id',
            )
        ));

        $this->add(array(
            'name' => 'fechainstalacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechainstalacion',
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
