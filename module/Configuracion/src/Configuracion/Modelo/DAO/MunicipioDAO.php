<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\Municipio;

class MunicipioDAO extends AbstractTableGateway {

    protected $table = 'municipio';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getMunicipios($idDepartamento = 0) {
        $municipios = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idMunicipio',
            'municipio',
        ));
        if ($idDepartamento != 0) {
            $select->where("municipio.idDepartamento = $idDepartamento");
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $municipios[] = new Municipio($dato);
        }
        return $municipios;
    }

    public function getMunicipio($idMunicipio = 0) {
        return new Municipio($this->select(array('idMunicipio' => $idMunicipio))->current()->getArrayCopy());
    }

    public function guardar(Municipio $municipioOBJ) {
        $idMunicipio = (int) $municipioOBJ->getIdMunicipio();
        $datos = $municipioOBJ->getArrayCopy();
        if ($idMunicipio == 0) {
            return $this->insert($datos);
        } else {
            if ($this->existeID($idMunicipio)) {
                return $this->update($datos, array('idMunicipio' => $idMunicipio));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idMunicipio = 0) {
        return $this->delete(array('idMunicipio' => (int) $idMunicipio));
    }

    public function existeID($idMunicipio = 0) {
        $id = (int) $idMunicipio;
        $rowset = $this->select(array('idMunicipio' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
