<?php

namespace TalentoHumano\Formularios;

use Zend\Form\Form;

class PermisoLaboralForm extends Form {

    public function __construct($action = '') {

        switch ($action) {
            case 'add':
                $onsubmit = 'return confirm("¿ DESEA REGISTRAR EL PERMISO LABORAL ?");';
                $required = true;
                $requiredObservacion = false;
                break;
            case 'edit':
                $onsubmit = 'return confirm("¿ DESEA GUARDAR LOS CAMBIOS ?")';
                $required = true;
                $requiredObservacion = false;
                break;
            case 'conceder':
                $onsubmit = 'return confirm("¿ DESEA CONCEDER ESTE PERMISO LABORAL ?")';
                $required = false;
                $requiredObservacion = true;
                break;
            case 'denegar':
                $onsubmit = 'return confirm("¿ DESEA DENEGAR ESTE PERMISO LABORAL ?")';
                $required = false;
                $requiredObservacion = true;
                break;
            case 'detail':
                $onsubmit = '';
                $required = false;
                $requiredObservacion = false;
                break;
            case 'delete':
                $onsubmit = 'return confirm("RECUERDE QUE LA INFORMACION DE ESTE PERMISO LABORAL SERA ELIMINADA COMPLETAMENTE DEL SISTEMA. \n\n  ¿ DESEA ELIMINAR ESTE PERMISO LABORAL ?")';
                $required = false;
                $requiredObservacion = false;
                break;
            default :
                $onsubmit = '';
                $required = false;
                $requiredObservacion = false;
                break;
        }

        parent::__construct('formPermisoLaboral');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_permiso_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'pk_permiso_id',
            )
        ));

        $this->add(array(
            'name' => 'fk_empleado_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'id' => 'fk_empleado_id',
            )
        ));

        $this->add(array(
            'name' => 'motivo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'style' => 'text-transform:uppercase',
                'id' => 'motivo',
            )
        ));

        $this->add(array(
            'name' => 'fechapermiso',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'min' => date('Y-m-d'),
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechapermiso',
            )
        ));

        $this->add(array(
            'name' => 'dias',
            'attributes' => array(
                'type' => 'number',
                'value' => '0',
                'min' => '0',
                'class' => 'form-control',                
                'readonly' => !$required,
                'id' => 'dias',
            )
        ));

        $this->add(array(
            'name' => 'horas',
            'attributes' => array(
                'type' => 'number',
                'value' => '0',
                'min' => '0',
                'max' => '24',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'horas',
            )
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => $required,
                'readonly' => !$required,
                'style' => 'height: 100px;',
                'id' => 'descripcion',
            )
        ));

        $this->add(array(
            'name' => 'observacion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => !$required,
                'readonly' => !$requiredObservacion,
                'style' => 'height: 100px;',
                'id' => 'observacion',
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
            'name' => 'respaldo',
            'type' => 'File',
            'attributes' => array(
                'type' => 'file',
                'class' => 'form-control',
                'accept' => 'image/png, application/pdf',
                'id' => 'respaldo',
            ),
        ));

        /* EMPLEADO */      
        
        $this->add(array(
            'name' => 'empleado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'empleado',
            )
        ));
        
        $this->add(array(
            'name' => 'identificacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'identificacion',
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

        $this->add(array(
            'name' => 'confirmadopor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'confirmadopor',
            )
        ));

        $this->add(array(
            'name' => 'fechahoraconfirm',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'fechahoraconfirm',
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
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Registrar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));     
    }

}
