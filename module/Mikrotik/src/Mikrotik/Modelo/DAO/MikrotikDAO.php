<?php

namespace Mikrotik\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Mikrotik\Modelo\Entidades\Pago;

class MikrotikDAO extends AbstractTableGateway {

    protected $table = 'pagos';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getSumPagosSaldoCaja($idUsuario = 0, $fechahoraapertura = '0000-00-00', $fechahoracierre = '0000-00-00') {
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new \Zend\Db\Sql\Expression("SUM(pagos.valor)"),
        ))->where("pagos.idUsuario = $idUsuario AND DATE(pagos.fechahorareg) >= '$fechahoraapertura' AND DATE(pagos.fechahorareg) <= '$fechahoracierre'");
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getFactura($pk_factura_id = 0) {
        return new Factura($this->select(array('pk_factura_id' => $pk_factura_id))->current()->getArrayCopy());
    }

    public function guardar(Pago $pagoOBJ) {
        $idPago = (int) $pagoOBJ->getIdPago();
        if ($idPago == 0) {
            return $this->insert($pagoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idPago)) {
                return $this->update($pagoOBJ->getArrayCopy(), array('pk_factura_id' => $idPago));
            } else {
                return 0;
            }
        }
    }

    public function eliminar($idPago = 0) {
        return $this->delete(array('pk_factura_id' => (int) $idPago));
    }

    public function existeID($idPago = 0) {
        $id = (int) $idPago;
        $rowset = $this->select(array('pk_factura_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function getInfoServicio($idFactura = 0) {
        $this->table = 'mensualidades_internet';
        $select = new Select($this->table);
        $select->columns(array(
                    'idServicio'
                ))->join('factura', 'mensualidades_internet.idMensualidad = factura.idMensualidad', array())
                ->where("factura.idFactura = $idFactura");
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0]['idServicio'];
        } else {
            return 0;
        }
    }

    public function pagarMesGratis($infoPagos = array(), $idUsuario = 0, $registradopor = '') {
        $connection = $this->getAdapter()->getDriver()->getConnection();
        $connection->beginTransaction();
        $this->table = 'pagos';
        try {
//            var_dump($infoPagos);
            foreach ($infoPagos as $info) {
                $insert = new Insert($this->table);
                $insert->values(array(
                    'idUsuario' => $idUsuario,
                    'idMensualidad' => $info['idMensualidad'],
                    'baseimponible' => $info['cargofijomes'],
                    'vlriva' => $info['vlriva'],
                    'totalpago' => $info['vlrmensualidad'], 
                    'registradopor' => $registradopor,
                    'fechahorareg' => date('Y-m-d H:i:s'),
                ));
                $this->insertWith($insert);
            }
//            exit();
            $connection->commit();
            return 1;
        } catch (\Exception $e) {
            $connection->rollback();
//            throw new \Exception($e);
            return 0;
        }
    }

}
