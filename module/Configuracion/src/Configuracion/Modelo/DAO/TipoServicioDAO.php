<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\TipoServicio;

class TipoServicioDAO extends AbstractTableGateway {

    protected $table = 'tipo_servicio';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTiposServicio() {
        $tiposservicio = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idTipoServicio',
            'tipo',
            'registradopor',
            'fechahorareg',
        ));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tiposservicio[] = new TipoServicio($dato);
        }
        return $tiposservicio;
    }

}
