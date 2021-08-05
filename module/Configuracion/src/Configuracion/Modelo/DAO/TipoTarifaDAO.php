<?php

namespace Configuracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Configuracion\Modelo\Entidades\TipoTarifa;

class TipoTarifaDAO extends AbstractTableGateway {

    protected $table = 'tipo_tarifa';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTiposTarifa($idTipoServicio = 0) {
        $tipos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'idTipoTarifa',
            'tipo',
            'registradopor',
            'fechahorareg',
        ))->join('tipo_servicio', 'tipo_tarifa.idTipoServicio = tipo_servicio.idTipoServicio', array('tiposervicio' => 'tipo'));
        if ($idTipoServicio != 0) {
            $select->where('tipo_tarifa.idTipoServicio = ' . $idTipoServicio);
        } else {
            $select->order('tipo_tarifa.idTipoTarifa DESC')->limit(10);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $tipos[] = new TipoTarifa($dato);
        }
        return $tipos;
    }

    public function getTipoTarifa($idTipoTarifa = 0) {
        return new TipoTarifa($this->select(array('idTipoTarifa' => $idTipoTarifa))->current()->getArrayCopy());
    }

    public function guardar(TipoTarifa $tipoTarifaOBJ) {
        $idTipoTarifa = (int) $tipoTarifaOBJ->getIdTipoTarifa();
        $datos = $tipoTarifaOBJ->getArrayCopy();
        unset($datos['tiposervicio']);
        if ($idTipoTarifa == 0) {
            return $this->insert($datos);
        } else {
            if ($this->existeID($idTipoTarifa)) {
                return $this->update($datos, array('idTipoTarifa' => $idTipoTarifa));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idTipoTarifa = 0) {
        return $this->delete(array('idTipoTarifa' => (int) $idTipoTarifa));
    }

    public function existeID($idTipoTarifa = 0) {
        $id = (int) $idTipoTarifa;
        $rowset = $this->select(array('idTipoTarifa' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function getTiposTarifaSelect($tiposervicio = 0) {
        $select = new Select('tipos_tarifa');
        $select->columns(array('idTipoTarifa', 'tipo'));
        $select->where('idTipoServicio = ' . $tiposervicio);
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

}
