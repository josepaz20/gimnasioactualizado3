<?php

namespace Usuarios\Formularios;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class UsuarioForm extends Form implements InputFilterProviderInterface {

    public function __construct($action = '', $listaEmpleados = array(), $listaRoles = array()) {
        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE USUARIO ?")';
                $txtBtnEnviar = 'Guardar';
                $required = true;
                break;
            case 'edit':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $txtBtnEnviar = 'Guardar';
                $required = true;
                break;
            case 'detail':
                $onsubmit = '';
                $required = false;
                break;
            case 'delete':
                $onsubmit = 'return confirm("¿ DESEA ELIMINAR ESTE USUARIO ?")';
                $txtBtnEnviar = 'Eliminar';
                $required = false;
                break;
            case 'activar':
                $onsubmit = 'return confirm("¿ DESEA ACTIVAR ESTE USUARIO ?")';
                $txtBtnEnviar = 'Activar';
                $required = false;
                break;
            case 'bloquear':
                $onsubmit = 'return confirm("¿ DESEA BLOQUEAR ESTE USUARIO ?")';
                $txtBtnEnviar = 'Bloquear';
                $required = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                $txtBtnEnviar = 'Enviar';
                break;
        }

        $disabled = false;
        if ($action == 'detail' || $action == 'delete') {
            $disabled = true;
        }

        parent::__construct('formUsuario');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);


        $this->add(array(
            'name' => 'fk_empleado_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $listaEmpleados,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => $disabled,
                'required' => $required,
                'class' => 'form-control',
                'onchange' => 'getLogin(this.value)',
                'id' => 'fk_empleado_id',
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
                'id' => 'fk_rol_id',
            )
        ));

        $this->add(array(
            'name' => 'login',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'required' => $required,
                'maxlength' => 20,
                'style' => 'text-transform:lowercase',
                'onchange' => 'existeLogin(this.value)',
                'id' => 'loginRegistro',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
                'readonly' => !$required,
                'required' => $required,
                'maxlength' => 100,
                'onblur' => 'verificarPassword()',
                'id' => 'password',
            )
        ));

        $this->add(array(
            'name' => 'passwordConfirm',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
                'readonly' => !$required,
                'required' => $required,
                'maxlength' => 100,
                'onblur' => 'verificarPassword()',
                'id' => 'passwordConfirm',
            )
        ));

        $this->add(array(
            'name' => 'nombresapellidos',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'required' => $required,
                'maxlength' => 100,
                'id' => 'nombresapellidos',
            )
        ));

        $this->add(array(
            'name' => 'sexo',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ..',
                'value_options' => array(
                    'Femenino' => 'Femenino',
                    'Masculino' => 'Masculino'
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'required' => $required,
                'class' => 'form-control',
                'id' => 'sexo',
            )
        ));


//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'pk_usuario_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'pk_USUARIO_id',
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
                'value' => $txtBtnEnviar,
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'fk_empleado_id' => array(
                'required' => false,
            )
        );
    }

}
