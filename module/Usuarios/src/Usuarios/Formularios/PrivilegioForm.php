<?php

namespace Usuarios\Formularios;

use Zend\Form\Form;

class PrivilegioForm extends Form {

    public function __construct($action = '', $listaRecursos = array(), $listaRoles = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE PRIVILEGIO ?")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTE PRIVILEGIO SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n   ¿ DESEA ELIMINAR ESTE PRIVILEGIO ?")';
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

        parent::__construct('formPrivilegio');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'fk_recursoacl_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $listaRecursos,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => $disabled,
                'required' => $required,
                'class' => 'form-control',
                'onchange' => 'cargarAcciones(this.text)',
                'id' => 'fk_recursoacl_id',
            )
        ));

        $this->add(array(
            'name' => 'fk_accion_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => $disabled,
                'required' => $required,
                'class' => 'form-control',
                'onchange' => 'existePrivilegio()',
                'id' => 'fk_accion_id',
            )
        ));

        $this->add(array(
            'name' => 'fk_rol_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $listaRoles,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => $disabled,
                'required' => $required,
                'class' => 'form-control',
                'onchange' => 'existePrivilegio()',
                'id' => 'fk_rol_id',
            )
        ));

        $this->add(array(
            'name' => 'permiso',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'OK' => 'OK',
                    'NO' => 'NO'
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => $disabled,
                'required' => $required,
                'class' => 'form-control',
                'id' => 'permiso',
            )
        ));

        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type' => 'textarea',
                'readonly' => !$required,
                'class' => 'form-control',
                'id' => 'observacion',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'pk_privilegio_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'pk_recursoacl_id',
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
