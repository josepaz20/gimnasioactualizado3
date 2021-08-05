<?php

namespace Hotspot\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Hotspot\Modelo\Entidades\Pregunta;

class PreguntaDAO extends AbstractTableGateway {

    protected $table = 'pregunta';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

//------------------------------------------------------------------------------

    public function getPreguntas($filtro = '') {
        $select = new Select($this->table);
        $select->columns(array(
            'idPregunta',
            'pregunta',
            'estado'
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

//------------------------------------------------------------------------------

    public function getPregunta($idPregunta = 0) {
        return new Pregunta($this->select(array('idPregunta' => $idPregunta))->current()->getArrayCopy());
    }

//------------------------------------------------------------------------------

    public function getPreguntaByIdentificacion($identificacion = 0) {
        if ($this->select(array('identificacion' => $identificacion))->current()) {
            return new Pregunta($this->select(array('identificacion' => $identificacion))->current()->getArrayCopy());
        }
        return new Pregunta();
    }

//------------------------------------------------------------------------------

 public function registrar(Pregunta $preguntaOBJ = null) {
        try {
            $this->table = 'pregunta';
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($preguntaOBJ->getArrayCopy());
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    
    //---------------------------------------------------------------------------
    public function editar(Pregunta $preguntaOBJ = null) {
        try {
            $this->table = 'pregunta';
            $update = new \Zend\Db\Sql\Update($this->table);
            $update->set($preguntaOBJ->getArrayCopy())->where('idPregunta = ' . $preguntaOBJ->getIdPregunta());
//            echo $update->getSqlString();
            return $this->updateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

//------------------------------------------------------------------------------
      public function eliminar($idPregunta = 0) {
        try {
            $this->table = 'pregunta';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Eliminado',
                
            ))->where(array('idPregunta = ' . $idPregunta));
            //echo $update->getSqlString();
            return $this->UpdateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
//------------------------------------------------------------------------------
      public function activar($idPregunta = 0) {
        try {
            $this->table = 'pregunta';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Activo',
                
            ))->where(array('idPregunta = ' . $idPregunta));
            //echo $update->getSqlString();
            return $this->UpdateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    //------------------------------------------------------------------------------

    public function existeID($idPregunta = 0) {
        $id = (int) $idPregunta;
        $rowset = $this->select(array('idPregunta' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

//------------------------------------------------------------------------------

    public function existeIdentificacion($identificacion = '') {
        $this->table = 'hotspot';
        $rowset = $this->select(array('identificacion' => $identificacion));
        $row = $rowset->current();
        if (!$row) {
            return 0;
        }
        return 1;
    }

//------------------------------------------------------------------------------

    public function getConsultaAbonado($identificacion = '') {
        $select = new Select($this->table);
        $select->columns(array(
            'idRegistro',
            'tipohotspot',
            'identificacion',
            'hotspot' => new \Zend\Db\Sql\Expression("IF(hotspot.tipohotspot = 'Persona Natural', CONCAT(hotspot.nombres, ' ', hotspot.apellidos), hotspot.razonsocial)"),
            'celular1',
            'celular2',
            'emailcontacto',
        ))->where("cliente.identificacion = $identificacion")->limit(1);
//        print $select->getSqlString();
        $clientes = $this->selectWith($select)->toArray();
        if (count($clientes) > 0) {
            return $clientes[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------

    public function getDireccionesRegistro($idRegistro = 0) {
        $this->table = 'direcciones';
        $select = new Select($this->table);
        $select->columns(array(
                    'idDireccion',
                    'direccion',
                ))->join('barrio', 'direcciones.idBarrio = barrio.idBarrio', array('barrio'))
                ->join('zona', 'barrio.idZona = zona.idZona', array('zona'))
                ->join('servicio', 'direcciones.idDireccion = servicio.idDireccion', array(
                    'idServicio',
                    'idTipoServicio',
                    'conceptofacturacion',
                    'estado',
                    'clasificacion',
                ))
                ->join('tipo_servicio', 'servicio.idTipoServicio = tipo_servicio.idTipoServicio', array('tiposervicio' => 'tipo'))
                ->join('sucursal', 'servicio.idSucursal = sucursal.idSucursal', array('sucursal'))
                ->where("servicio.estado != 'Eliminado' AND servicio.idRegistro = $idRegistro");
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

//------------------------------------------------------------------------------
}
