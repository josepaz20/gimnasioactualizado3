<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\TipoVivienda;

class TipoViviendaDAO extends AbstractTableGateway {

    protected $table = 'tipo_vivienda';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTiposVivienda() {
        $tiposvivienda = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idTipoVivienda',
            'tipo',
            'estado',
            'registradopor',
            'fechahorareg',
        ));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tiposvivienda[] = new TipoVivienda($dato);
        }
        return $tiposvivienda;
    }

}
