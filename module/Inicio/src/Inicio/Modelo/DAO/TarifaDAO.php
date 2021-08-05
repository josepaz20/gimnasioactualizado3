<?php

namespace Contrataciontv\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Contrataciontv\Modelo\Entidades\Tarifa;

class TarifaDAO extends AbstractTableGateway {

    protected $table = 'tarifas_tv';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTarifas($where = array()) {
        $tarifas = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'idTarifa',
                    'nombretarifa',
                    'valor',
                    'fechaini',
                    'estado',
                    'registradopor',
                ))->join('sucursal', 'tarifas_tv.idSucursal = sucursal.idSucursal', array('sucursal'))
                ->where($where);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tarifas[] = new Tarifa($dato);
        }
        return $tarifas;
    }

    public function getTarifa($idTarifa = 0) {
        return new Tarifa($this->select(array('idTarifa' => $idTarifa))->current()->getArrayCopy());
    }

    public function guardar(Tarifa $tarifaOBJ) {
        $idTarifa = (int) $tarifaOBJ->getIdTarifa();
        if ($idTarifa == 0) {
            $infoArray = $tarifaOBJ->getArrayCopy();
            unset($infoArray['idTarifa']);
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($infoArray);
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } else {
            if ($this->existeID($idTarifa)) {
                return $this->update($tarifaOBJ->getArrayCopy(), array('idTarifa' => $idTarifa));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idTarifa = 0) {
        return $this->delete(array('idTarifa' => (int) $idTarifa));
    }

    public function existeID($idTarifa = 0) {
        $id = (int) $idTarifa;
        $rowset = $this->select(array('idTarifa' => $id));
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
        $tarifas = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idTarifa',
            'sucursal',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tarifas[] = new Sucursal($dato);
        }
        return $tarifas;
    }

}
