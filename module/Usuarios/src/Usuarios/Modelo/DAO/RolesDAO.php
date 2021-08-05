<?php

namespace Usuarios\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Usuarios\Modelo\Entidades\Rol;

class RolesDAO extends AbstractTableGateway {

    protected $table = 'roles';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRoles($filtro = array()) {
        $roles = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_rol_id',
            'rol',
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
            $roles[] = new Rol($dato);
        }
        return $roles;
    }

    public function getRolesArray($filtro = array()) {
        $roles = array();
        $select = new Select($this->table);
        $select->columns(array(
            'rol',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $roles[] = $dato['rol'];
        }
        return $roles;
    }

    public function getRol($pk_rol_id = 0) {
        return new Rol($this->select(array('pk_rol_id' => $pk_rol_id))->current()->getArrayCopy());
    }

    public function getRolByNombre($rol = '') {
        return new Rol($this->select(array('rol' => $rol))->current()->getArrayCopy());
    }

    public function guardar(Rol $rolOBJ) {
        $idRol = (int) $rolOBJ->getPk_rol_id();
        if ($idRol == 0) {
            return $this->insert($rolOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idRol)) {
                return $this->update($rolOBJ->getArrayCopy(), array('pk_rol_id' => $idRol));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idRol = 0) {
        return $this->delete(array('pk_rol_id' => (int) $idRol));
    }

    public function existeID($idRol = 0) {
        $id = (int) $idRol;
        $rowset = $this->select(array('pk_rol_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

//******************************************************************************

    public function getRolesInitACL($filtro = array()) {
        $select = new Select($this->table);
        $select->columns(array(
            'pk_rol_id',
            'rol',
        ));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

    public function getPadresRolInitACL($filtro = array()) {
        $this->table = 'rol_padres';
        $select = new Select($this->table);
        $select->columns(array(
            'fk_rol_padre_id',
        ))->join('roles', 'roles.pk_rol_id = rol_padres.fk_rol_padre_id', array('rolPadre' => 'rol'));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

}
