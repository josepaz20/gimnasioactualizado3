<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\Barrio;

class BarrioDAO extends AbstractTableGateway {

    protected $table = 'barrio';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getBarrios($idZona = 0) {
        $barrios = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idBarrio',
            'idZona',
            'barrio',
            'estado',
            'registradopor',
            'fechahorareg',
        ));
        if ($idZona != 0) {
            $select->where("barrio.idZona = $idZona");
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $barrios[] = new Barrio($dato);
        }
        return $barrios;
    }

    public function getBarrio($idBarrio = 0) {
        return new Barrio($this->select(array('idBarrio' => $idBarrio))->current()->getArrayCopy());
    }

    public function guardar(Barrio $barrioOBJ) {
        $idBarrio = (int) $barrioOBJ->getidBarrio();
        if ($idBarrio == 0) {
            $infoArray = $barrioOBJ->getArrayCopy();
            unset($infoArray['idBarrio']);
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($infoArray);
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } else {
            if ($this->existeID($idBarrio)) {
                return $this->update($barrioOBJ->getArrayCopy(), array('idBarrio' => $idBarrio));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idBarrio = 0) {
        return $this->delete(array('idBarrio' => (int) $idBarrio));
    }

    public function existeID($idBarrio = 0) {
        $id = (int) $idBarrio;
        $rowset = $this->select(array('idBarrio' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
