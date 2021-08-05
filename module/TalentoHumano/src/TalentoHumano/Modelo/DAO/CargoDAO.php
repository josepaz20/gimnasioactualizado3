<?php

namespace TalentoHumano\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use TalentoHumano\Modelo\Entidades\Cargo;

class CargoDAO extends AbstractTableGateway {

    protected $table = 'cargo';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getCargos($filtro = '') {
        $cargos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_cargo_id',
            'cargo',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
        //print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $cargos[] = new Cargo($dato);
        }
        return $cargos;
    }

    public function getCargo($pk_cargo_id = 0) {
        return new Cargo($this->select(array('pk_cargo_id' => $pk_cargo_id))->current()->getArrayCopy());
    }

    public function guardar(Cargo $cargoOBJ = null) {
        $idCargo = (int) $cargoOBJ->getPk_cargo_id();
        if ($idCargo == 0) {
            return $this->insert($cargoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idCargo)) {
                return $this->update($cargoOBJ->getArrayCopy(), array('pk_cargo_id' => $idCargo));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idCargo = 0) {
        $id = (int) $idCargo;
        $rowset = $this->select(array('pk_cargo_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
