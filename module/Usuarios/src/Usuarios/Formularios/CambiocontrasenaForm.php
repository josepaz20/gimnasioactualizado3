<?php

namespace Usuarios\Formularios;

use Zend\Form\Form;

class CambiocontrasenaForm extends Form {

    public function __construct() {

        parent::__construct('formCambiarcontrasena');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', '../../usuarios/usuarios/cambiarcontrasena');
        $this->setAttribute('onsubmit', 'guardarNuevoPassword(); return false');

        $this->add(array(
            'name' => 'passwordactual',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
                'required' => true,
                'maxlength' => 100,
                'id' => 'passwordactual',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
                'required' => true,
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
                'required' => true,
                'maxlength' => 100,
                'onblur' => 'verificarPassword()',
                'id' => 'passwordConfirm',
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
                'value' => 'Cambiar Contraseña',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
