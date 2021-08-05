<?php

namespace Usuarios\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Usuarios\Modelo\Entidades\Privilegio;

class PrivilegiosDAO extends AbstractTableGateway {

    protected $table = 'privilegios';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getPrivilegios($filtro = array()) {
        $privilegios = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_privilegio_id',
                    'fk_recursoacl_id',
                    'fk_accion_id',
                    'fk_rol_id',
                    'permiso',
                    'estado',
                    'registradopor',
                    'fechahorareg',
                ))->join('recursosacl', 'recursosacl.pk_recursoacl_id = privilegios.fk_recursoacl_id', array('recursoacl'))
                ->join('acciones', 'acciones.pk_accion_id = privilegios.fk_accion_id', array('accion'))
                ->join('roles', 'roles.pk_rol_id = privilegios.fk_rol_id', array('rol'));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $privilegio = array(
                'privilegioOBJ' => new Privilegio($dato),
                'recursoacl' => $dato['recursoacl'],
                'accion' => $dato['accion'],
                'rol' => $dato['rol'],
            );
            $privilegios[] = $privilegio;
        }
        return $privilegios;
    }

    public function getPrivilegio($pk_privilegio_id = 0) {
        return new Privilegio($this->select(array('pk_privilegio_id' => $pk_privilegio_id))->current()->getArrayCopy());
    }

    public function guardar(Privilegio $privilegioOBJ) {
        $idPrivilegio = (int) $privilegioOBJ->getPk_privilegio_id();
        if ($idPrivilegio == 0) {
            return $this->insert($privilegioOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idPrivilegio)) {
                return $this->update($privilegioOBJ->getArrayCopy(), array('pk_privilegio_id' => $idPrivilegio));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idPrivilegio = 0) {
        return $this->delete(array('pk_privilegio_id' => (int) $idPrivilegio));
    }

    public function existeID($idPrivilegio = 0) {
        $id = (int) $idPrivilegio;
        $rowset = $this->select(array('pk_privilegio_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function existePrivilegio($idRecurso = 0, $idAccion = 0, $idRol = 0) {
        $rowset = $this->select(array(
            'fk_recursoacl_id' => $idRecurso,
            'fk_accion_id' => $idAccion,
            'fk_rol_id' => $idRol,
        ));
        $row = $rowset->current();
        if (!$row) {
            return 0;
        }
        return 1;
    }

//------------------------------------------------------------------------------

    public function getPrivilegiosInitACL($filtro = array()) {
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_privilegio_id',
                ))->join('recursosacl', 'recursosacl.pk_recursoacl_id = privilegios.fk_recursoacl_id', array('recursoacl'))
                ->join('acciones', 'acciones.pk_accion_id = privilegios.fk_accion_id', array('accion'))
                ->join('roles', 'roles.pk_rol_id = privilegios.fk_rol_id', array('rol'));
        if (count($filtro) != 0) {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

}
