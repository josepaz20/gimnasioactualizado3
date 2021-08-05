<?php

namespace Hotspot\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Hotspot\Modelo\Entidades\Producto;

class ProductoDAO extends AbstractTableGateway {

    protected $table = 'inventario_gimnasio3';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

//------------------------------------------------------------------------------

    public function getRegistros($filtro = '') {
        $select = new Select($this->table);
        $select->columns(array(
            'idRegistro',
            'cantidad',
            'producto',
           
            'fechahorareg',
            'proveedor',
            'ingreso',
            'devolucion',
            'pendiente',
            'telefono',
            'estado'
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

//------------------------------------------------------------------------------

      public function getProducto($idRegistro = 0) {
        return new Producto($this->select(array('idRegistro' => $idRegistro))->current()->getArrayCopy());
    }

//------------------------------------------------------------------------------

    public function getRegistroByIdentificacion($identificacion = 0) {
        if ($this->select(array('identificacion' => $identificacion))->current()) {
            return new Producto($this->select(array('identificacion' => $identificacion))->current()->getArrayCopy());
        }
        return new Producto();
    }

//------------------------------------------------------------------------------
    
 public function registrar(Producto $registroOBJ = null) {
        try {
            $this->table = 'inventario_gimnasio3';
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values( $registroOBJ->getArrayCopy());
//            echo $insert->getSqlString();
            return $this->insertWith($insert);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
   public function editar (Producto $registroOBJ = null) {
        try {
            $this->table = 'inventario_gimnasio3';
            $update = new \Zend\Db\Sql\Update($this->table);
            $update->set($registroOBJ->getArrayCopy())->where('idRegistro = ' . $registroOBJ->getIdRegistro());
//            echo $update->getSqlString();
            return $this->updateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }


//------------------------------------------------------------------------------

      public function eliminar($idRegistro = 0) {
        try {
            $this->table = 'inventario_gimnasio3';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Eliminado',
                
            ))->where(array('idRegistro = ' . $idRegistro ));
            //echo $update->getSqlString();
            return $this->UpdateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
   

//------------------------------------------------------------------------------
 public function activar($idRegistro = 0) {
        try {
            $this->table = 'inventario_gimnasio3';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Activo',
                
            ))->where(array('idRegistro = ' . $idRegistro ));
            //echo $update->getSqlString();
            return $this->UpdateWith($update);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
   

//------------------------------------------------------------------------------
     public function existeID($idRegistro = 0) {
        $id = (int) $idRegistro;
        $rowset = $this->select(array('idRegistro' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }
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
    
    public function getServicioImprimir($idRegistro= 0) {
        $this->table = 'inventario_gimnasio3';
        $select = new Select($this->table);
        $select->columns(array(
            'idRegistro',
            
            'cantidad',
            'producto',
            'fechahorareg',
            'proveedor',
            'telefono',
            'estado'
           
        ))->where("idRegistro = " . $idRegistro);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) > 0) {
            return $datos[0];
        } else {
            return null;
        }
    }

//------------------------------------------------------------------------------
}
