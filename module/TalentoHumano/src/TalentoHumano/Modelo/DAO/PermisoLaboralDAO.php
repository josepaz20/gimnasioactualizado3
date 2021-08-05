<?php

namespace TalentoHumano\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use TalentoHumano\Modelo\Entidades\PermisoLaboral;

class PermisoLaboralDAO extends AbstractTableGateway {

    protected $table = 'permiso_laboral';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
        
    public function getPermisosLaborales($idEmpleado = 0) {
        $permisos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_permiso_id',
            'fk_empleado_id',
            'motivo',
            'fechapermiso',
            'dias',
            'horas',
            'descripcion',
            'respaldo',
            'estado',
            'observacion',
            'registradopor',
            'fechahorareg',
            'modificadopor',
            'fechahoramod',
            'confirmadopor',
            'fechahoraconfirm',
        ));
        $select->join('empleado', 'empleado.pk_empleado_id = permiso_laboral.fk_empleado_id', array(
                    'pk_empleado_id',                    
                    'empleado' => new Expression("CONCAT(apellidos, ' ', nombres)"),
                    'identificacion'
                
            )
        );
        if ($idEmpleado != 0){            
            $select->where('permiso_laboral.fk_empleado_id = ' . $idEmpleado );
            $select->where->notLike('permiso_laboral.estado', 'Eliminado');
        }

        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $permiso = array(
                'permisoLaboralOBJ' => new PermisoLaboral($dato),
                'pk_empleado_id' => $dato['pk_empleado_id'],
                'empleado' => $dato['empleado'],
            );
            $permisos[] = $permiso;
        }        
        return $permisos;
    }
    
    //OBTENER INFORMACION DEL EMPLEADO EN EL PERMISO LABORAL YA REGUUISTRADO
    public function getInfoEmpleado($idEmpleado = 0) {        
        $select = new Select('empleado');
        $select->columns(array(
            'pk_empleado_id',                    
            'empleado' => new Expression("CONCAT(apellidos, ' ', nombres)"),
            'identificacion'
        ));

        if ($idEmpleado != 0){
            $select->where('empleado.pk_empleado_id = ' . $idEmpleado);
        }

        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            return array(
                'pk_empleado_id' => $dato['pk_empleado_id'],
                'empleado' => $dato['empleado'],
                'identificacion' => $dato['identificacion'],
            );            
        }                
    }
    
    //OBTENER INFORMACION DEL EMPLEADO CUANDO SE VA A REGISTRAR EL PERMISO LABORAL
    public function getInfoEmpleadoRegistro($idUsuario = 0) {        
        $select = new Select('usuario');
        $select->columns(array(
            'pk_usuario_id',                                            
        ));
        $select->join('empleado', 'empleado.pk_empleado_id = usuario.fk_empleado_id', array(
                    'pk_empleado_id',
                    'empleado' => new Expression("CONCAT(apellidos, ' ', nombres)"),
                    'identificacion')
                );
        if ($idUsuario != 0){
            $select->where('usuario.pk_usuario_id = ' . $idUsuario);
        }

        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            return array(
                'fk_empleado_id' => $dato['pk_empleado_id'],
                'empleado' => $dato['empleado'],
                'identificacion' => $dato['identificacion'],
                'pk_usuario_id' => $dato['pk_usuario_id'],
            );            
        }                
    }
    
    public function getPermisoLaboral($pk_permiso_id = 0) {
        return new PermisoLaboral($this->select(array('pk_permiso_id' => $pk_permiso_id))->current()->getArrayCopy());
    }

    public function confirmarPermisoLaboral(PermisoLaboral $postArray = null) {
        $idPermisoLaboral = $postArray->getPk_permiso_id();
        $data = array(
            'estado' => $postArray->getEstado(),
            'observacion' => $postArray->getObservacion(),
            'confirmadopor' => $postArray->getConfirmadopor(),
            'fechahoraconfirm' => $postArray->getFechahoraconfirm(),
        );

        return $this->update($data, (array('pk_permiso_id' => $idPermisoLaboral)));
    }

    public function actualizarPermisoLaboral(PermisoLaboral $postArray = null) {
        $idPermisoLaboral = $postArray->getPk_permiso_id();
        $data = array(
            'motivo' => $postArray->getMotivo(),
            'fechapermiso' => $postArray->getFechapermiso(),
            'dias' => $postArray->getDias(),
            'horas' => $postArray->getHoras(),
            'descripcion' => $postArray->getDescripcion(),
            'modificadopor' => $postArray->getModificadopor(),
            'fechahoramod' => $postArray->getFechahoramod(),
        );

        return $this->update($data, (array('pk_permiso_id' => $idPermisoLaboral)));
    }

    public function guardar(PermisoLaboral $postArray = null) {
        $idPermisoLaboral = (int) $postArray->getPk_permiso_id();
        if ($idPermisoLaboral == 0) {
            return $this->insert($postArray->getArrayCopy());
        } else {
            if ($this->existeID($idPermisoLaboral)) {
                return $this->update($postArray->getArrayCopy(), array('pk_permiso_id' => $idPermisoLaboral));
            } else {
                return 0;
            }
        }
    }
    //CAMBIO DE ESTADO
    public function eliminar(PermisoLaboral $postArray = null) {
        print_r($postArray);
        $idPermisoLaboral = $postArray->getPk_permiso_id();
        $data = array(
            'estado' => $postArray->getEstado(),            
            'modificadopor' => $postArray->getModificadopor(),
            'fechahoramod' => $postArray->getFechahoramod(),
        );

        return $this->update($data, (array('pk_permiso_id' => $idPermisoLaboral)));
    }

    public function existeID($idPermisoLaboral = 0) {
        $id = (int) $idPermisoLaboral;
        $rowset = $this->select(array('pk_permiso_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }    

    //----------------------------------------------------------------------------------------------------
    //METODO UTILIZADO PARA DESCARGA DE ARCHIVOS
    public function getRespaldo($idPermisoLaboral = 0) {
        $select = new Select($this->table);
        $select->columns(array(
            'respaldo',
        ))->where(array('permiso_laboral.pk_permiso_id' => $idPermisoLaboral))->limit(1);
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0]['respaldo'];
        } else {
            return 'SIN RUTA';
        }
    }

}
