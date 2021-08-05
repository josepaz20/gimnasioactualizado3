<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\Sucursal;

class SucursalDAO extends AbstractTableGateway {

    protected $table = 'sucursal';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getSucursales($filtro = array()) {
        $sucursales = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idSucursal',
            'sucursal',
            'estado',
            'registradopor',
            'fechahorareg',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $sucursales[] = new Sucursal($dato);
        }
        return $sucursales;
    }

    public function getSucursal($idSucursal = 0) {
        return new Sucursal($this->select(array('idSucursal' => $idSucursal))->current()->getArrayCopy());
    }

    public function guardar(Sucursal $sucursalOBJ) {
        $idSucursal = (int) $sucursalOBJ->getIdSucursal();
        if ($idSucursal == 0) {
            $infoArray = $sucursalOBJ->getArrayCopy();
            unset($infoArray['idSucursal']);
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($infoArray);
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } else {
            if ($this->existeID($idSucursal)) {
                return $this->update($sucursalOBJ->getArrayCopy(), array('idSucursal' => $idSucursal));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idSucursal = 0) {
        return $this->delete(array('idSucursal' => (int) $idSucursal));
    }

    public function existeID($idSucursal = 0) {
        $id = (int) $idSucursal;
        $rowset = $this->select(array('idSucursal' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function getCentroPobladoByMunicipio($idMcpo = 0) {
        $this->table = 'centro_poblado';
        $select = new Select($this->table);
        $select->columns(array('pk_centro_poblado_id',))->where("fk_municipio_id = $idMcpo")->limit(1);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['pk_centro_poblado_id'];
    }

    public function getSucursalesSelect($filtro = array()) {
        $sucursales = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idSucursal',
            'sucursal',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $sucursales[] = new Sucursal($dato);
        }
        return $sucursales;
    }
}
