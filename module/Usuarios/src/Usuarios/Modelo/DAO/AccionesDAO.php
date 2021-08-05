<?php

namespace Usuarios\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Usuarios\Modelo\Entidades\Accion;

class AccionesDAO extends AbstractTableGateway {

    protected $table = 'acciones';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getAcciones($filtro = array()) {
        $acciones = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_accion_id',
            'accion',
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
            $acciones[] = new Accion($dato);
        }
        return $acciones;
    }

    public function getAccionesArray($filtro = array()) {
        $acciones = array();
        $select = new Select($this->table);
        $select->columns(array(
            'accion',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
        $select->order('accion');
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $acciones[] = $dato['accion'];
        }
        return $acciones;
    }

    public function getAccion($pk_accion_id = 0) {
        return new Accion($this->select(array('pk_accion_id' => $pk_accion_id))->current()->getArrayCopy());
    }

    public function getAccionByNombre($accion = '') {
        echo $accion.':<br>';
        return new Accion($this->select(array('accion' => $accion))->current()->getArrayCopy());
    }

    public function guardar(Accion $accionOBJ) {
        $idAccion = (int) $accionOBJ->getPk_accion_id();
        if ($idAccion == 0) {
            return $this->insert($accionOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idAccion)) {
                return $this->update($accionOBJ->getArrayCopy(), array('pk_accion_id' => $idAccion));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idAccion = 0) {
        return $this->delete(array('pk_accion_id' => (int) $idAccion));
    }

    public function existeID($idAccion = 0) {
        $id = (int) $idAccion;
        $rowset = $this->select(array('pk_accion_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
