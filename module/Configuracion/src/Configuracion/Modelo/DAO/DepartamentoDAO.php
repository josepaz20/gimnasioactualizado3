<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\Departamento;

class DepartamentoDAO extends AbstractTableGateway {

    protected $table = 'departamento';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getDepartamentos($filtro = '') {
        $departamentos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idDepartamento',
            'departamento',
        ))->order("departamento ASC");
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $departamentos[] = new Departamento($dato);
        }
        return $departamentos;
    }

    public function getDepartamento($idDepartamento = 0) {
        return new Departamento($this->select(array('idDepartamento' => $idDepartamento))->current()->getArrayCopy());
    }

    public function guardar(Departamento $departamentoOBJ) {
        $idDepartamento = (int) $departamentoOBJ->getIdDepartamento();
        $datos = $departamentoOBJ->getArrayCopy();
        if ($idDepartamento == 0) {
            return $this->insert($datos);
        } else {
            if ($this->existeID($idDepartamento)) {
                return $this->update($datos, array('idDepartamento' => $idDepartamento));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idDepartamento = 0) {
        return $this->delete(array('idDepartamento' => (int) $idDepartamento));
    }

    public function existeID($idDepartamento = 0) {
        $id = (int) $idDepartamento;
        $rowset = $this->select(array('idDepartamento' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
