<?php

namespace Contrataciontv\Modelo\Entidades;

class Abonado {

    private $idServicioTV;
    private $fk_empresa_id;
    private $fk_persona_id;
    private $idSucursal;
    private $fk_barrio_id;
    private $cliente;
    private $identificacion;
    private $conceptofacturacion;
    private $tarifa;
    private $numtvsprincipal;
    private $numtvsadicionales;
    private $fechainstalacion;
    private $instalargratis;
    private $pagoinstalacion;
    private $modalidadpago;
    private $diasinstalacion;
    private $facturaren;
    private $dirinstalacion;
    private $latitud;
    private $longitud;
    private $diacorte;
    private $estado;
    private $observacion;
    private $soportelegal;
    private $legalizadopor;
    private $fechalegalizacion;
    private $registradopor;
    private $modificadopor;
    private $fechahorareg;
    private $fechahoramod;

    public function __construct(array $datos = null) {
        $this->fechahoramod = '0000-00-00 00:00:00';
        if (is_array($datos)) {
            $this->exchangeArray($datos);
        }
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function exchangeArray($data) {
        $metodos = get_class_methods($this);
        foreach ($data as $key => $value) {
            $metodo = 'set' . ucfirst($key);
            if (in_array($metodo, $metodos)) {
                $this->$metodo($value);
            }
        }
    }

//------------------------------------------------------------------------------

    public function getIdServicioTV() {
        return $this->idServicioTV;
    }

    public function getFk_empresa_id() {
        return $this->fk_empresa_id;
    }

    public function getFk_persona_id() {
        return $this->fk_persona_id;
    }

    public function getIdSucursal() {
        return $this->idSucursal;
    }

    public function getFk_barrio_id() {
        return $this->fk_barrio_id;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getIdentificacion() {
        return $this->identificacion;
    }

    public function getConceptofacturacion() {
        return $this->conceptofacturacion;
    }

    public function getTarifa() {
        return $this->tarifa;
    }

    public function getNumtvsprincipal() {
        return $this->numtvsprincipal;
    }

    public function getNumtvsadicionales() {
        return $this->numtvsadicionales;
    }

    public function getFechainstalacion() {
        return $this->fechainstalacion;
    }

    public function getInstalargratis() {
        return $this->instalargratis;
    }

    public function getPagoinstalacion() {
        return $this->pagoinstalacion;
    }

    public function getModalidadpago() {
        return $this->modalidadpago;
    }

    public function getDiasinstalacion() {
        return $this->diasinstalacion;
    }

    public function getFacturaren() {
        return $this->facturaren;
    }

    public function getDirinstalacion() {
        return $this->dirinstalacion;
    }

    public function getLatitud() {
        return $this->latitud;
    }

    public function getLongitud() {
        return $this->longitud;
    }

    public function getDiacorte() {
        return $this->diacorte;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function getSoportelegal() {
        return $this->soportelegal;
    }

    public function getLegalizadopor() {
        return $this->legalizadopor;
    }

    public function getFechalegalizacion() {
        return $this->fechalegalizacion;
    }

    public function getRegistradopor() {
        return $this->registradopor;
    }

    public function getModificadopor() {
        return $this->modificadopor;
    }

    public function getFechahorareg() {
        return $this->fechahorareg;
    }

    public function getFechahoramod() {
        return $this->fechahoramod;
    }

    public function setIdServicioTV($idServicioTV) {
        $this->idServicioTV = $idServicioTV;
    }

    public function setFk_empresa_id($fk_empresa_id) {
        $this->fk_empresa_id = $fk_empresa_id;
    }

    public function setFk_persona_id($fk_persona_id) {
        $this->fk_persona_id = $fk_persona_id;
    }

    public function setIdSucursal($idSucursal) {
        $this->idSucursal = $idSucursal;
    }

    public function setFk_barrio_id($fk_barrio_id) {
        $this->fk_barrio_id = $fk_barrio_id;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    public function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    public function setConceptofacturacion($conceptofacturacion) {
        $this->conceptofacturacion = $conceptofacturacion;
    }

    public function setTarifa($tarifa) {
        $this->tarifa = $tarifa;
    }

    public function setNumtvsprincipal($numtvsprincipal) {
        $this->numtvsprincipal = $numtvsprincipal;
    }

    public function setNumtvsadicionales($numtvsadicionales) {
        $this->numtvsadicionales = $numtvsadicionales;
    }

    public function setFechainstalacion($fechainstalacion) {
        $this->fechainstalacion = $fechainstalacion;
    }

    public function setInstalargratis($instalargratis) {
        $this->instalargratis = $instalargratis;
    }

    public function setPagoinstalacion($pagoinstalacion) {
        $this->pagoinstalacion = $pagoinstalacion;
    }

    public function setModalidadpago($modalidadpago) {
        $this->modalidadpago = $modalidadpago;
    }

    public function setDiasinstalacion($diasinstalacion) {
        $this->diasinstalacion = $diasinstalacion;
    }

    public function setFacturaren($facturaren) {
        $this->facturaren = $facturaren;
    }

    public function setDirinstalacion($dirinstalacion) {
        $this->dirinstalacion = $dirinstalacion;
    }

    public function setLatitud($latitud) {
        $this->latitud = $latitud;
    }

    public function setLongitud($longitud) {
        $this->longitud = $longitud;
    }

    public function setDiacorte($diacorte) {
        $this->diacorte = $diacorte;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setObservacion($observacion) {
        $this->observacion = strtoupper($observacion);
    }

    public function setSoportelegal($soportelegal) {
        $this->soportelegal = $soportelegal;
    }

    public function setLegalizadopor($legalizadopor) {
        $this->legalizadopor = $legalizadopor;
    }

    public function setFechalegalizacion($fechalegalizacion) {
        $this->fechalegalizacion = $fechalegalizacion;
    }

    public function setRegistradopor($registradopor) {
        $this->registradopor = $registradopor;
    }

    public function setModificadopor($modificadopor) {
        $this->modificadopor = $modificadopor;
    }

    public function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    public function setFechahoramod($fechahoramod) {
        $this->fechahoramod = $fechahoramod;
    }

}
