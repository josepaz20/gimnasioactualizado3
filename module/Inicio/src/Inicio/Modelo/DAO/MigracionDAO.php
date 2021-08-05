<?php

namespace Inicio\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class MigracionDAO extends AbstractTableGateway {

    protected $table = '';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function existeBarrio($sucursal = '', $nuncomuna = 0, $barrio = '') {
        $this->table = 'barrio';
        $select = new Select($this->table);
        $select->columns(array(
                    'existe' => new \Zend\Db\Sql\Expression("COUNT(barrio.idBarrio)"),
                ))->join('zona', 'barrio.idZona = zona.idZona', array())
                ->join('sucursal', 'zona.idSucursal = sucursal.idSucursal', array())
                ->where("barrio.barrio = '$barrio' AND zona.zona = '$nuncomuna' AND sucursal.sucursal = '$sucursal'");
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if ($datos[0]['existe'] > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function existeTarifa($sucursal = '', $tarifa = '', $vlrtarifa = 0, $idTipoServicio = 0) {
        $this->table = 'tarifa';
        $select = new Select($this->table);
        $select->columns(array(
                    'existe' => new \Zend\Db\Sql\Expression("COUNT(tarifa.idTarifa)"),
                ))->join('tipo_tarifa', 'tarifa.idTipoTarifa = tipo_tarifa.idTipoTarifa', array())
                ->join('sucursal', 'tarifa.idSucursal = sucursal.idSucursal', array())
                ->where("tipo_tarifa.idTipoServicio = $idTipoServicio AND tipo_tarifa.tipo = '$tarifa' AND tarifa.valor = $vlrtarifa AND sucursal.sucursal = '$sucursal'");
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if ($datos[0]['existe'] > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------

    public function getClienteByIdentificacion($identificacion = '') {
        $this->table = 'cliente';
        $select = new Select($this->table);
        $select->columns(array(
            'idCliente',
        ))->where("cliente.identificacion = '$identificacion'")->limit(1);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------

    public function getBarrio($idSucursal = 0, $nuncomuna = 0, $barrio = '') {
        $this->table = 'barrio';
        $select = new Select($this->table);
        $select->columns(array(
                    'idBarrio',
                ))->join('zona', 'barrio.idZona = zona.idZona', array())
                ->where("barrio.barrio = '$barrio' AND zona.zona = '$nuncomuna' AND zona.idSucursal = $idSucursal")
                ->limit(1);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------

    public function getTarifa($idSucursal = 0, $idTipoServicio = 0, $tarifa = '', $vlrtarifa = 0) {
        $this->table = 'tarifa';
        $select = new Select($this->table);
        $select->columns(array(
                    'idTarifa',
                    'unidadanchobanda',
                    'velsubida',
                    'velbajada',
                ))->join('tipo_tarifa', 'tarifa.idTipoTarifa = tipo_tarifa.idTipoTarifa', array())
                ->where("tipo_tarifa.idTipoServicio = $idTipoServicio AND tipo_tarifa.tipo = '$tarifa' AND tarifa.valor = $vlrtarifa AND tarifa.idSucursal = $idSucursal")
                ->limit(1);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------

    public function getServicioByIdentificacion($identificacion = '') {
        $this->table = 'servicio';
        $select = new Select($this->table);
        $select->columns(array(
                    'idServicio',
                ))->join('cliente', 'servicio.idCliente = cliente.idCliente', array())
                ->where("cliente.identificacion = '$identificacion'");
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) == 1) {
            return $datos[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------
}
