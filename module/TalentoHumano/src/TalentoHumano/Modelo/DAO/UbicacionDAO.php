<?php

namespace TalentoHumano\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use TalentoHumano\Modelo\Entidades\Departamento;
use TalentoHumano\Modelo\Entidades\Municipio;
use TalentoHumano\Modelo\Entidades\CentroPoblado;
use TalentoHumano\Modelo\Entidades\Ubicacion;

class UbicacionDAO extends AbstractTableGateway {

    protected $table = '';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getDepartamentos($filtro = '') {
        $departamentos = array();
        $this->table = 'departamento';
        $select = new Select($this->table);
        $select->columns(array(
            'idDepartamento',
            'departamento',
        ));
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

    public function getMunicipios($filtro = '') {
        $municipios = array();
        $this->table = 'municipio';
        $select = new Select($this->table);
        $select->columns(array(
            'idMunicipio',
            'idDepartamento',
            'municipio',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $municipios[] = new Municipio($dato);
        }
        return $municipios;
    }

    public function getCentrosPoblados($filtro = '') {
        $centrospoblados = array();
        $this->table = 'centro_poblado';
        $select = new Select($this->table);
        $select->columns(array(
            'pk_centro_poblado_id',
            'fk_municipio_id',
            'centropoblado',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $centrospoblados[] = new CentroPoblado($dato);
        }
        return $centrospoblados;
    }

    public function getUbicacion($idMunicipio = 0) {

        $this->table = 'municipio';
        $select = new Select($this->table);
        $select->columns(array(
            'idMunicipio'
        ))->join('departamento', 'departamento.idDepartamento = municipio.idDepartamento', array('idDepartamento'));
        $select->where('municipio.idMunicipio= ' . $idMunicipio);

//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            return new Ubicacion($dato);
        }
        return null;
    }

    public function guardar(Ubicacion $ubicacionOBJ = null) {
        $idUbicacion = (int) $ubicacionOBJ->getPk_ubicacion_id();
        if ($idUbicacion == 0) {
            return $this->insert($ubicacionOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idUbicacion)) {
                return $this->update($ubicacionOBJ->getArrayCopy(), array('pk_ubicacion_id' => $idUbicacion));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idUbicacion = 0) {
        $id = (int) $idUbicacion;
        $rowset = $this->select(array('pk_ubicacion_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

//    public function getPreguntasFormularioSeleccionar($filtro = '') {
//        $ubicaciones = array();
//        $select = new Select($this->table);
//        $select->columns(array(
//            'pk_pregunta_id',
//            'pregunta',
//            'tipoPregunta',
//            'clasificacion',
//            'registradoPor',
//            'fechaHoraReg',
//            'estado',
//        ));
//        if ($filtro != '') {
//            $select->where($filtro);
//        }
////        print $select->getSqlString();
//        $datos = $this->selectWith($select)->toArray();
//        foreach ($datos as $dato) {
//            $ubicaciones[] = new Pregunta($dato);
//        }
//        return $ubicaciones;
//    }

    public function getUbicacionPregunta($idPregunta = 0) {
        $ubicaciones = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_ubicacion_id',
                    'ubicacion',
                    'puntos',
                    'estado',
                    'registradoPor',
                    'fechaHoraReg',
                ))
                ->join('pregunta_ubicacion', 'pregunta_ubicacion.fk_ubicacion_id = ubicacion.pk_ubicacion_id', array())
                ->where('pregunta_ubicacion.fk_pregunta_id = ' . $idPregunta);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ubicaciones[] = new Ubicacion($dato);
        }
        return $ubicaciones;
    }

}
