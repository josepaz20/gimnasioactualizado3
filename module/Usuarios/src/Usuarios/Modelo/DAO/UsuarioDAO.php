<?php

namespace Usuarios\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Expression;
use Usuarios\Modelo\Entidades\Usuario;

class UsuarioDAO extends AbstractTableGateway {

    protected $table = 'usuario';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getUsuarios($filtro = array()) {
        $usuarios = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_usuario_id',
                    'fk_empleado_id',
                    'fk_rol_id',
                    'login',
                    'nombresapellidos',
                    'estado',
                    'registradopor',
                    'fechahorareg',
                ))->join('roles', 'roles.pk_rol_id = usuario.fk_rol_id', array('rol'))
                ->where('pk_usuario_id != 1');
        if (count($filtro) != 0) {
            if (array_key_exists('idSucursal', $filtro)) {
                if ($filtro['idSucursal'] != -1) {
                    $select->join('usuario_sucursal', 'usuario.pk_usuario_id = usuario_sucursal.idUsuario')->where('usuario_sucursal.idSucursal = ' . $filtro['idSucursal']);
                }
            } else {
                $select->where($filtro);
            }
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $usuarios[] = array(
                'usuarioOBJ' => new Usuario($dato),
                'rol' => $dato['rol']
            );
        }
        return $usuarios;
    }

    public function getUsuario($pk_usuario_id = 0) {
        return new Usuario($this->select(array('pk_usuario_id' => $pk_usuario_id))->current()->getArrayCopy());
    }

    public function existeLogin($login = '') {
        $select = new Select($this->table);
        $select->columns(array('existe' => new Expression('COUNT(pk_usuario_id)')))
                ->where(array('login' => $login));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if ($datos[0]['existe'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function guardar(Usuario $usuarioOBJ, $idSucursalAsignada = 0) {
        $idUsuario = (int) $usuarioOBJ->getPk_usuario_id();
        if ($idUsuario == 0) {
            $infoArray = $usuarioOBJ->getArrayCopy();
            unset($infoArray['pk_usuario_id']);
            $this->table = 'usuario';
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($infoArray);
//            echo $insert->getSqlString();
            $ok = $this->insertWith($insert);
            $idUsuarioInsert = $this->getLastInsertValue();
            if ($idSucursalAsignada != 0) {
                $this->table = 'usuario_sucursal';
                $insert = new \Zend\Db\Sql\Insert($this->table);
                $insert->values(array(
                    'idUsuario' => $idUsuarioInsert,
                    'idSucursal' => $idSucursalAsignada,
                    'estado' => 'Activo',
                    'registradopor' => $usuarioOBJ->getRegistradopor(),
                    'fechahorareg' => date('Y-m-d H:i:s'),
                ));
//                echo $insert->getSqlString();
                $this->insertWith($insert);
            }
            return $ok;
        } else {
            if ($this->existeID($idUsuario)) {
                return $this->update($usuarioOBJ->getArrayCopy(), array('pk_usuario_id' => $idUsuario));
            } else {
                return 0;
            }
        }
    }

    public function actualizar(Usuario $usuarioOBJ) {
        $update = new Update($this->table);
        $update->set(array(
            'sexo' => $usuarioOBJ->getSexo(),
            'fk_rol_id' => $usuarioOBJ->getFk_rol_id(),
            'modificadopor' => $usuarioOBJ->getModificadopor(),
            'fechahoramod' => $usuarioOBJ->getFechahoramod(),
        ))->where(array('pk_usuario_id' => $usuarioOBJ->getPk_usuario_id()));
        return $this->updateWith($update);
    }

    public function eliminar($idUsuario = 0, $modificadopor = '') {
        $update = new Update($this->table);
        $update->set(array(
            'estado' => 'Eliminado',
            'modificadopor' => $modificadopor,
            'fechahoramod' => date('Y-m-d H:i:s'),
        ))->where(array('pk_usuario_id' => $idUsuario));
        return $this->updateWith($update);
    }

    public function activar($idUsuario = 0, $modificadopor = '') {
        $update = new Update($this->table);
        $update->set(array(
            'estado' => 'Activo',
            'modificadopor' => $modificadopor,
            'fechahoramod' => date('Y-m-d H:i:s'),
        ))->where(array('pk_usuario_id' => $idUsuario));
        return $this->updateWith($update);
    }

    public function bloquear($idUsuario = 0, $modificadopor = '') {
        $update = new Update($this->table);
        $update->set(array(
            'estado' => 'Bloqueado',
            'modificadopor' => $modificadopor,
            'fechahoramod' => date('Y-m-d H:i:s'),
        ))->where(array('pk_usuario_id' => $idUsuario));
        return $this->updateWith($update);
    }

    public function existeID($idUsuario = 0) {
        $id = (int) $idUsuario;
        $rowset = $this->select(array('pk_usuario_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function cambiarcontrasena($idUsuario = 0, $newpassword = '', $passwordseguro = '', $modificadopor = '') {
        $update = new Update($this->table);
        $update->set(array(
            'password' => $newpassword,
            'passwordseguro' => $passwordseguro,
            'modificadopor' => $modificadopor,
            'fechahoramod' => date('Y-m-d H:i:s'),
        ))->where(array('pk_usuario_id' => $idUsuario));
        return $this->updateWith($update);
    }

    public function getSucursales() {
        $select = new Select('sucursal');
        $select->columns(array('idSucursal', 'sucursal'));
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

    public function getSucursalesAsignadas($idUsuario = 0) {
        $select = new Select('usuario_sucursal');
        $select->columns(array('idSucursal'))->where("idUsuario = $idUsuario");
//        print $select->getSqlString();
        $sucursales = $this->selectWith($select)->toArray();
        $idsSucursales = array();
        foreach ($sucursales as $idSucursal) {
            $idsSucursales[] = $idSucursal['idSucursal'];
        }
        return $idsSucursales;
    }

    public function gestionSucursalesUsuario($eliminar = array(), $agregar = array(), $idUsuario = 0, $registradopor = '') {
        $this->table = 'usuario_sucursal';
        $insert = new \Zend\Db\Sql\Insert($this->table);
        $delete = new \Zend\Db\Sql\Delete($this->table);
        $connection = $this->getAdapter()->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            foreach ($eliminar as $idSucursal) {
                $delete->where("idSucursal = $idSucursal AND idUsuario = $idUsuario");
                $this->deleteWith($delete);
            }
            foreach ($agregar as $idSucursal) {
                $insert->values(array(
                    'idSucursal' => $idSucursal,
                    'idUsuario' => $idUsuario,
                    'estado' => 'Activo',
                    'registradopor' => $registradopor,
                    'fechahorareg' => date('Y-m-d H:i:s'),
                ));
                $this->insertWith($insert);
            }
            $connection->commit();
            return 1;
        } catch (\Exception $e) {
            $connection->rollback();
            return 0;
        }
    }

    // SE USA EN MODULO CAJA - CONTROLADOR: AdministracionController
    public function getUsuariosSinCaja($filtro = '') {
        $usuarios = array();
        $this->table = 'usuario';
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_usuario_id',
                    'fk_empleado_id',
                    'fk_rol_id',
                    'login',
                    'nombresapellidos',
                    'estado',
                    'registradopor',
                    'fechahorareg',
                ))->join('roles', 'roles.pk_rol_id = usuario.fk_rol_id', array('rol'))
                ->where("usuario.pk_usuario_id != 1 AND usuario.estado = 'Activo' AND (SELECT COUNT(cajas.idCaja) FROM cajas WHERE cajas.idUsuario = usuario.pk_usuario_id) = 0")
                ->order("usuario.nombresapellidos ASC");
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $usuarios[] = new Usuario($dato);
        }
        return $usuarios;
    }

}
