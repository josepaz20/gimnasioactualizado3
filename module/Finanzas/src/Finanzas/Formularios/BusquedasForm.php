<?php

namespace Finanzas\Formularios;

use Zend\Form\Form;

class BusquedasForm extends Form {

    public function __construct($url = '') {
        parent::__construct('formBusquedas');
        $this->setAttribute('method', 'get');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $url);
//        $this->setAttribute('onsubmit', 'return validarBusquedaOTs()');

        $this->add(array(
            'name' => 'identificacionFiltro',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'minlength' => 5,
                'maxlength' => 20,
                'id' => 'identificacionFiltro',
            )
        ));

        $this->add(array(
            'name' => 'nombresFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 50,
                'style' => 'text-transform:uppercase',
                'id' => 'nombresFiltro',
            )
        ));

        $this->add(array(
            'name' => 'apellidosFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 50,
                'style' => 'text-transform:uppercase',
                'id' => 'apellidosFiltro',
            )
        ));
        
        $this->add(array(
            'name' => 'razonsocialFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 3,
                'maxlength' => 50,
                'style' => 'text-transform:uppercase',
                'id' => 'razonsocialFiltro',
            )
        ));

        $this->add(array(
            'name' => 'referenciapagoFiltro',
            'attributes' => array(
                'type' => 'number',
                'class' => 'form-control',
                'min' => 0,
                'minlength' => 5,
                'maxlength' => 20,
                'id' => 'referenciapagoFiltro',
            )
        ));

        $this->add(array(
            'name' => 'direccionFiltro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'minlength' => 5,
                'maxlength' => 50,
                'style' => 'text-transform:uppercase',
                
                'id' => 'direccionFiltro',
            )
        ));
            $this->add(array(
            'name' => 'fechadesde',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'style' => 'text-transform:uppercase',
                'id' => 'fechadesde',
            )
        ));
              $this->add(array(
            'name' => 'fechahasta',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control',
                'style' => 'text-transform:uppercase',
                'id' => 'fechadesde',
            )
        ));
        
//------------------------------------------------------------------------------
    }

}
