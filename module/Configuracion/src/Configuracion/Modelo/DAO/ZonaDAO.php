<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\Zona;

class ZonaDAO extends AbstractTableGateway {

    protected $table = 'zona';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getZonas($idSucursal = 0) {
        $zonas = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idZona',
            'zona',
            'estado',
            'registradopor',
            'fechahorareg',
        ))->join('sucursal', 'zona.idSucursal = sucursal.idSucursal', array('sucursal'));
        if ($idSucursal != 0) {
            $select->where("zona.idSucursal = $idSucursal");
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $zonas[] = new Zona($dato);
        }
        return $zonas;
    }

    public function getZona($idZona = 0) {
        return new Zona($this->select(array('idZona' => $idZona))->current()->getArrayCopy());
    }

    public function guardar(Zona $zonaOBJ) {
        $idZona = (int) $zonaOBJ->getIdZona();
        if ($idZona == 0) {
            $infoArray = $zonaOBJ->getArrayCopy();
            unset($infoArray['idZona']);
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($infoArray);
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } else {
            if ($this->existeID($idZona)) {
                return $this->update($zonaOBJ->getArrayCopy(), array('idZona' => $idZona));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idZona = 0) {
        return $this->delete(array('idZona' => (int) $idZona));
    }

    public function existeID($idZona = 0) {
        $id = (int) $idZona;
        $rowset = $this->select(array('idZona' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
