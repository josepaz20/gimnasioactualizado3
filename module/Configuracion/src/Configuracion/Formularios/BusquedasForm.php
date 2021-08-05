<?php

namespace Configuracion\Formularios;

use Zend\Form\Form;

class BusquedasForm extends Form {

    public function __construct($url = '', $tiposServicio = array()) {
        parent::__construct('formBusquedas');
        $this->setAttribute('method', 'get');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $url);
        $this->setAttribute('onsubmit', 'return validarBusqueda()');


        $this->add(array(
            'name' => 'idTipoServicioFiltro',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $tiposServicio,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'id' => 'idTipoServicioFiltro',
            )
        ));
    }

}
