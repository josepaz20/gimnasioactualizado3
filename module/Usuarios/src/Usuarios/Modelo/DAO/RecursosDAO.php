<?php

namespace Usuarios\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Usuarios\Modelo\Entidades\Recurso;

class RecursosDAO extends AbstractTableGateway {

    protected $table = 'recursosacl';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRecursos($filtro = array()) {
        $recursos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_recursoacl_id',
            'recursoacl',
            'estado',
            'registradopor',
            'fechahorareg',
        ))->order('recursoacl ASC');
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $recursos[] = new Recurso($dato);
        }
        return $recursos;
    }

    public function getRecursosArray($filtro = array()) {
        $recursos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'recursoacl',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
        $select->order('recursoacl');
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $recursos[] = $dato['recursoacl'];
        }
        return $recursos;
    }

    public function getRecurso($pk_recursoacl_id = 0) {
        return new Recurso($this->select(array('pk_recursoacl_id' => $pk_recursoacl_id))->current()->getArrayCopy());
    }

    public function guardar(Recurso $recursoOBJ) {
        $idRecurso = (int) $recursoOBJ->getPk_recursoacl_id();
        if ($idRecurso == 0) {
            return $this->insert($recursoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idRecurso)) {
                return $this->update($recursoOBJ->getArrayCopy(), array('pk_recursoacl_id' => $idRecurso));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idRecurso = 0) {
        return $this->delete(array('pk_recursoacl_id' => (int) $idRecurso));
    }

    public function existeID($idRecurso = 0) {
        $id = (int) $idRecurso;
        $rowset = $this->select(array('pk_recursoacl_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

//******************************************************************************    

    public function getRecursosInitACL($filtro = array()) {
        $select = new Select($this->table);
        $select->columns(array(
            'pk_recursoacl_id',
            'recursoacl',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

}
