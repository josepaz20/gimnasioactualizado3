<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\TipoIdentificacion;

class TipoIdentificacionDAO extends AbstractTableGateway {

    protected $table = 'tipo_identificacion';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTiposIdentificacion() {
        $tiposidentificacion = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idTipoIdentificacion',
            'tipo',
            'registradopor',
            'fechahorareg',
        ));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tiposidentificacion[] = new TipoIdentificacion($dato);
        }
        return $tiposidentificacion;
    }

}
