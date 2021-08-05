<?php

namespace Hotspot\Formularios;

use Zend\Form\Form;

class PreguntaForm extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA PREGUNTA ? ")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA PREGUNTA SERA ELIMINADA  ¿ DESEA ELIMINAR ESTA PREGUNTA?")';
                $required = false;
                break;
            
             case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE LA PREGUNTA SERA ACTUALIZADA  ¿ DESEA ACTUALIZAR ESTA PREGUNTA?")';
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

        parent::__construct('formPregunta');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $accion);
        $this->setAttribute('onsubmit', $onsubmit);
        
         $this->add(array(
            'name' => 'idPregunta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idPregunta',
            )
        ));

        $this->add(array(
            'name' => 'pregunta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'id' => 'pregunta',
            )
        ));

        
//------------------------------------------------------------------------------

       $this->add(array(
            'name' => 'estado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'estado',
            )
        ));
    }

}
