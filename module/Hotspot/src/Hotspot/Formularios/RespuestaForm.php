<?php

namespace Hotspot\Formularios;

use Zend\Form\Form;

class RespuestaForm extends Form {

    public function __construct($accion = '', $tiposIdentificacion = array()) {
        switch ($accion) {
            case 'registrar':
                $onsubmit = 'return confirm(" ¿ DESEA REGISTRAR ESTA RESPUESTA ? ")';
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
                $onsubmit = 'return confirm("RECUERDE QUE LA RESPUESTA SERA ELIMINADA  ¿ DESEA ELIMINAR ESTA RESPUESTA?")';
                $required = false;
                break;
            
             case 'activar':
                $onsubmit = 'return confirm("RECUERDE QUE LA RESPUESTA SERA ACTUALIZADA  ¿ DESEA ACTUALIZAR ESTA RESPUESTA?")';
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

        parent::__construct('formRespuesta');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $accion);
        $this->setAttribute('onsubmit', $onsubmit);
        
         $this->add(array(
            'name' => 'idRespuesta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'idRespuesta',
            )
        ));

        $this->add(array(
            'name' => 'respuesta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => true,
                'id' => 'respuesta',
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
