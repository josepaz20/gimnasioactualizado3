<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Configuracion\Modelo\Entidades\Departamento;
use Configuracion\Modelo\Entidades\Municipio;
use Configuracion\Modelo\Entidades\CentroPoblado;
use Configuracion\Modelo\Entidades\Ubicacion;

class UbicacionDAO extends AbstractTableGateway {

    protected $table = '';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getUbicacion($idCentroPoblado = 0) {
        $this->table = 'centro_poblado';
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_centro_poblado_id',
                    'centropoblado'
                ))->join('municipio', 'municipio.pk_municipio_id = centro_poblado.fk_municipio_id', array('pk_municipio_id', 'municipio'))
                ->join('departamento', 'departamento.pk_departamento_id = municipio.fk_departamento_id', array('pk_departamento_id', 'departamento'));
        $select->where('centro_poblado.pk_centro_poblado_id = ' . $idCentroPoblado);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            return new Ubicacion($dato);
        }
        return null;
    }

    public function getUbicacionCentrosPoblados($idMunicipio = 0) {
        $ubicaciones = array();
        $this->table = 'centro_poblado';
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_centro_poblado_id',
                    'centropoblado'
                ))->join('municipio', 'municipio.pk_municipio_id = centro_poblado.fk_municipio_id', array('pk_municipio_id', 'municipio'))
                ->join('departamento', 'departamento.pk_departamento_id = municipio.fk_departamento_id', array('pk_departamento_id', 'departamento'));
        $select->where('municipio.pk_municipio_id = ' . $idMunicipio);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ubicaciones[] = new Ubicacion($dato);
        }
        return $ubicaciones;
    }

    public function getDepartamentos($filtro = '') {
        $departamentos = array();
        $this->table = 'departamento';
        $select = new Select($this->table);
        $select->columns(array(
            'pk_departamento_id',
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
            'pk_municipio_id',
            'fk_departamento_id',
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

    public function guardarCentroPoblado(CentroPoblado $centroPobladoOBJ = null) {
        $this->table = 'centro_poblado';
        $idCentroPoblado = (int) $centroPobladoOBJ->getPk_centro_poblado_id();
        if ($idCentroPoblado == 0) {
            $insert = new Insert($this->table);
            $insert->values($centroPobladoOBJ->getArrayCopy());
            return $this->insertWith($insert);
        } else {
            if ($this->existeID($idCentroPoblado)) {
                return $this->update($centroPobladoOBJ->getArrayCopy(), array('pk_centro_poblado_id' => $idCentroPoblado));
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

}
