<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Inicio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BandejaentradaController extends AbstractActionController {

    private $rutaArchivos = '/home/ARCHIVOS_JOSANDRO/MIGRACION';
//    private $rutaArchivos = 'C:\ARCHIVOS_JOSANDRO\MIGRACION';
    private $migracionDAO;
    private $ordenTrabajoDAO;
    private $cobroDAO;

//------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'idUsuario' => $sesionUsuario->pk_usuario_id,
                'idSucursal' => $sesionUsuario->idSucursal,
                'idEmpleado' => $sesionUsuario->fk_empleado_id,
                'idRol' => $sesionUsuario->fk_rol_id,
                'rol' => $sesionUsuario->rol,
                'nombresapellidos' => substr(trim($sesionUsuario->nombresapellidos), 0, 20),
            );
        } else {
            $infoSession = array(
                'idUsuario' => 0,
                'idSucursal' => '',
                'idEmpleado' => 0,
                'idRol' => 0,
                'rol' => '',
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

//------------------------------------------------------------------------------    

    public function getMigracionDAO() {
        if (!$this->migracionDAO) {
            $sm = $this->getServiceLocator();
            $this->migracionDAO = $sm->get('Inicio\Modelo\DAO\MigracionDAO');
        }
        return $this->migracionDAO;
    }

    public function getOrdenTrabajoDAO() {
        if (!$this->ordenTrabajoDAO) {
            $sm = $this->getServiceLocator();
            $this->ordenTrabajoDAO = $sm->get('Ordenestrabajo\Modelo\DAO\OrdenTrabajoDAO');
        }
        return $this->ordenTrabajoDAO;
    }

    public function getCobroDAO() {
        if (!$this->cobroDAO) {
            $sm = $this->getServiceLocator();
            $this->cobroDAO = $sm->get('Cobros\Modelo\DAO\CobroDAO');
        }
        return $this->cobroDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($idAbonado = 0, $action = '', $idSucursal = 0) {
        $infoSesion = $this->getInfoSesionUsuario();
        $idsSucursales = explode(';', $infoSesion['idSucursal']);
        $sucursales = $this->getPersonaDAO()->getSucursales($idsSucursales);
        $selectSucursales = array();
        foreach ($sucursales as $sucursal) {
            $selectSucursales[$sucursal['idSucursal']] = $sucursal['sucursal'];
        }
        $zonas = $this->getZonaDAO()->getZonas(array('zona.estado' => 'Activo', 'zona.idSucursal' => $idSucursal));
        foreach ($zonas as $zona) {
            $zonaOBJ = $zona['zonaOBJ'];
            $listaZonas[$zonaOBJ->getPk_zona_id()] = $zonaOBJ->getZona();
        }
        $form = new AbonadoForm($action, $selectSucursales, $listaZonas);
        $form->get('idSucursal')->setValue($idSucursal);
        if ($idAbonado != 0) {
            $abonadoOBJ = $this->getAbonadoDAO()->getSucursal($idAbonado);
            $form->bind($abonadoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $session = $this->getInfoSesionUsuario();
        $numOTs = 0;
        $numOTsInstalaciones = 0;
        $numOTsCortes = 0;
        $numOTsTraslados = 0;
        $numOTsMantenimientos = 0;
        $numVentasSinEntregar = 0;
        if ($session['idRol'] == 13) { // 13 --> asignacion de equipos
            $numOTs = $this->getOrdenTrabajoDAO()->getContOTs('aprovisionamiento', 1);
        }
        if ($session['idRol'] == 3) { // 3 --> asesor comercial
            $ventasArray = $this->getCobroDAO()->getCobrosInstalacionByIdEmpleado($session['idEmpleado'], 0);
            $numVentasSinEntregar = count($ventasArray);
        }
        if ($session['idRol'] == 6) { // 6 --> coordinador de tecnicos
            $numOTsInstalaciones = 0; //$this->getOrdenTrabajoDAO()->getContOTs('asignacion', 1);
            $numOTsCortes = 0; //$this->getOrdenTrabajoDAO()->getContOTs('asignacion', 6);
            $numOTsTraslados = 0; //$this->getOrdenTrabajoDAO()->getContOTs('asignacion', 4);
            $numOTsMantenimientos = 0; //$this->getOrdenTrabajoDAO()->getContOTs('asignacion', 3);
        }
        if ($session['idRol'] == 0 || $session['idRol'] == 5 || $session['idRol'] == 7) { // Tecnicos
            $numOTsInstalaciones = $this->getOrdenTrabajoDAO()->getContOTsTecnicos(1, $session['idEmpleado']);
            $numOTsCortes = $this->getOrdenTrabajoDAO()->getContOTsTecnicos(6, $session['idEmpleado']);
            $numOTsTraslados = $this->getOrdenTrabajoDAO()->getContOTsTecnicos(4, $session['idEmpleado']);
            $numOTsMantenimientos = $this->getOrdenTrabajoDAO()->getContOTsTecnicos(3, $session['idEmpleado']);
        }
        return new ViewModel(array(
            'numOTs' => $numOTs,
            'numOTsInstalaciones' => $numOTsInstalaciones,
            'numOTsCortes' => $numOTsCortes,
            'numOTsTraslados' => $numOTsTraslados,
            'numOTsMantenimientos' => $numOTsMantenimientos,
            'numVentasSinEntregar' => $numVentasSinEntregar,
            'numOTs' => $numOTs,
            'idRol' => $session['idRol'],
        ));
    }

//------------------------------------------------------------------------------    

    public function verificarAction() {
        $consultas = array();
        $errores = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $importar = $this->params()->fromPost('importar', '');
            if ($importar != '') {
                $errores[] = "VERIFICACION DE ARCHIVO";
                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 10485760)); //  10 MB
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('csv',)));
                $httpadapter->setValidators(array($filesize, $extension), $files['respaldo']['name']);
                if ($httpadapter->isValid()) {
                    $errores[] = "ARCHIVO VALIDO";
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['respaldo']['name'], PATHINFO_EXTENSION);
                    $archivo = 'ARCHIVO_MIGRACION.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if (is_file($this->rutaArchivos . '/' . $archivo)) {
                        $errores[] = "ELIMINANDO ARCHIVO . . .";
                        unlink($this->rutaArchivos . '/' . $archivo);
                    }
                    if ($httpadapter->receive($files['respaldo']['name'])) {
                        $comunasNeiva = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
                        $comunasIbague = array(6, 7, 8, 9);
                        $comunasEspinal = array(1, 2);
                        $comunasSanVicente = array(10);
                        $comunasTesalia = array(1, 2, 3, 10);
                        $comunasPaicol = array(10);
                        $file = fopen($this->rutaArchivos . '/' . $archivo, "r");
                        $fila = 1;
                        while (($datos = fgetcsv($file, 0, ';')) == true) {
                            $numcol = count($datos);                            
                            if ($numcol == 29) {
//                                echo trim($datos[0]) . '-';
                                $codigo = trim($datos[0]);
                                $razonsocial = trim($datos[1]);
                                $apellidos = trim($datos[2]);
                                $nombres = trim($datos[3]);
                                $estado = trim($datos[4]);
                                $tipopersona = trim($datos[5]);
                                $identificacion = trim($datos[6]);
                                $tipovivienda = trim($datos[7]);
                                $comuna = trim($datos[8]);
                                $barrio = trim($datos[9]);
                                $direccion = trim($datos[10]);
                                $telefono = trim($datos[11]);
                                $email = trim($datos[12]);
                                $celular = trim($datos[13]);
                                $osinstall = trim($datos[14]);
                                $fechainstall = trim($datos[15]);
                                $tarifa = trim($datos[16]);
                                $vlrtarifa = $this->limpiarPuntosComas(trim($datos[17]));
//                                $vlrtarifa = str_replace('.', '', $vlrtarifa);
//                                $vlrtarifa = str_replace(',', '', $vlrtarifa);
                                $numtvppal = trim($datos[18]);
                                $numtvadd = trim($datos[19]);
                                //--------------------------------------------------
                                $deuda = $this->limpiarPuntosComas(trim($datos[20]));
                                $fechacorte = trim($datos[21]);
                                $estrato = trim(substr($datos[22], 7));
                                $fecharegistro = trim($datos[23]);
                                $username = trim($datos[24]);
                                $password = trim($datos[25]);
                                $serial = trim($datos[26]);
                                $clasificacion = trim($datos[27]);
                                $sucursal = trim($datos[28]);
                                //**************************************************
                                //**************************************************

//                                $servicioArray = $this->getMigracionDAO()->getServicioByIdentificacion($identificacion);
//                                if ($servicioArray) {
//                                    $idServicio = $servicioArray['idServicio'];
//                                    $consultas[] = "INSERT INTO hd(idServicio, idTarifa, conceptofacaturacion, tarifa, fechainstalacion, pagoinstalacion, estado, registradopor, fechahorareg) VALUES($idServicio, 0, '$tarifa', $vlrtarifa, '$fechainstall', 0, '$estado', 'Sistema', '$fecharegistro');";
//                                } else {
//                                    $consultas[] = "$identificacion - insert into hd(idServicio, idTarifa, conceptofacaturacion, tarifa, fechainstalacion, pagoinstalacion, estado, registradopor, fechahorareg) values(111, 0, '$tarifa', $vlrtarifa, '$fechainstall', 0, '$estado', 'Sistema', '$fecharegistro');";
//                                }
                                
                                ////**************************************************
                                //**************************************************
                                switch ($tipopersona) {
                                    case 'P.JURIDICA':
                                        if ($razonsocial == '') {
                                            $errores[] = "[$fila] PERSONA JURIDICA RAZON SOCIAL NO DEFINIDA";
                                        }
                                        break;
                                    case 'P. NATURAL':
                                        if ($nombres == '') {
                                            $nombres = 'N.N';
//                                            $errores[] = "[$fila] PERSONA NATURAL NOMBRES NO DEFINIDO";
                                        }
                                        if ($apellidos == '') {
                                            $errores[] = "[$fila] PERSONA NATURAL APELLIDOS NO DEFINIDO";
                                        }
                                        break;
                                    default:
                                        $errores[] = "[$fila] TIPO PERSONA NO DEFINIDO";
                                        break;
                                }
                                //------------------------------------------------------------------
                                if ($comuna != '') {
                                    $nuncomuna = trim(substr($comuna, 6));
                                    switch ($sucursal) {
                                        case 'NEIVA':
                                            if (!in_array($nuncomuna, $comunasNeiva)) {
                                                $errores[] = "[$fila] COMUNA NO $nuncomuna ENCONTRADA EN LA SUCURSAL NEIVA";
                                            }
                                            break;
                                        case 'IBAGUE':
                                            if (!in_array($nuncomuna, $comunasIbague)) {
                                                $errores[] = "[$fila] COMUNA $nuncomuna NO ENCONTRADA EN LA SUCURSAL IBAGUE";
                                            }
                                            break;
                                        case 'ESPINAL':
                                            if (!in_array($nuncomuna, $comunasEspinal)) {
                                                $errores[] = "[$fila] COMUNA $nuncomuna NO ENCONTRADA EN LA SUCURSAL ESPINAL";
                                            }
                                            break;
                                        case 'SAN VICENTE':
                                            if (!in_array($nuncomuna, $comunasSanVicente)) {
                                                $errores[] = "[$fila] COMUNA $nuncomuna NO ENCONTRADA EN LA SUCURSAL SAN VICENTE";
                                            }
                                            break;
                                        case 'TESALIA':
                                            if (!in_array($nuncomuna, $comunasTesalia)) {
                                                $errores[] = "[$fila] COMUNA $nuncomuna NO ENCONTRADA EN LA SUCURSAL TESALIA";
                                            }
                                            break;
                                        case 'PAICOL':
                                            if (!in_array($nuncomuna, $comunasPaicol)) {
                                                $errores[] = "[$fila] COMUNA $nuncomuna NO ENCONTRADA EN LA SUCURSAL PAICOL";
                                            }
                                            break;
                                        default:
                                            $errores[] = "[$fila] SUCURSAL NO DEFINIDA";
                                            break;
                                    }
                                } else {
                                    $errores[] = "[$fila] COMUNA NO DEFINIDA";
                                    $nuncomuna = 0;
                                }
                                //------------------------------------------------------------------
                                if ($barrio != '') {
                                    if (!$this->getMigracionDAO()->existeBarrio($sucursal, $nuncomuna, $barrio)) {
                                        $errores[] = "[$fila] BARRIO << $barrio >> NO ENCONTRADO EN COMUNA $nuncomuna DE LA SUCURSAL $sucursal";
                                    }
                                } else {
                                    $errores[] = "[$fila] BARRIO NO DEFINIDO";
                                }
                                //------------------------------------------------------------------
                                if ($tarifa != '' && $vlrtarifa != '') {
                                    if ((strpos($tarifa, 'BASICO') !== false && strpos($tarifa, 'MBPS') === false) || strpos($tarifa, 'TV GPON') !== false || strpos($tarifa, 'TV EMPLEADOS') !== false || (strpos($tarifa, 'CORTESIA') !== false && strpos($tarifa, 'INTERNET') === false) || strpos($tarifa, 'ESAL') !== false || strpos($tarifa, 'BASICA ESPECIAL') !== false) {
                                        $idTipoServicio = 2;
                                    } else {
                                        $idTipoServicio = 1;
                                    }
                                    if (strpos($tarifa, 'BASICO') !== false && strpos($tarifa, 'MBPS') === false && strpos($tarifa, 'BASICO 20 T') === false && strpos($tarifa, 'BASICO 23 T') === false) {
                                        $partesTarifa = explode(' ', $tarifa);
                                        if (count($partesTarifa) != 2) {
                                            $errores[] = "[$fila] TARIFA BASICO SIN BASE ESPECIFICADA";
                                        }
                                    } else {
                                        if (!$this->getMigracionDAO()->existeTarifa($sucursal, $tarifa, $vlrtarifa, $idTipoServicio)) {
                                            $errores[] = "[$fila] TARIFA $tarifa-$vlrtarifa NO ENCONTRADA EN SUCURSAL $sucursal, CON VALOR $vlrtarifa";
                                        }
                                    }
                                } else {
                                    $errores[] = "[$fila] TARIFA O VALOR TARIFA NO DEFINIDO";
                                }                                
                            } else {
                                $errores[] = "[$fila] EL NUMERO DE COLUMNAS REQUERIDAS NO ES CORRECTO. COLUMNAS REQUERIDAS: 29, ENCONTRADAS: $numcol";
                            }
                            $fila++;
                        }
                        fclose($file);
                    } else {
                        $errores[] = "ERROR AL VALIDAR ARCHIVO";
                        $dataError = $httpadapter->getMessages();
                        foreach ($dataError as $key => $row) {
                            $errores[] = $row;
                        }
                    }
                } else {
                    $errores[] = "ERROR AL VALIDAR ARCHIVO";
                    $dataError = $httpadapter->getMessages();
                    foreach ($dataError as $key => $row) {
                        $errores[] = $row;
                    }
                }
            }
        }
        $verificacion = '<pre>';
        foreach ($errores as $error) {
            $verificacion .= $error . '<br>';
        }
        $verificacion .= '</pre>';
        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(array(
            'contErrores' => count($errores) - 3,
            'verificacion' => $verificacion,
        ));
//        echo $verificacion;
        return $jsonModel;
    }

//------------------------------------------------------------------------------    

    public function importarAction() {
        $errores = array();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $importar = $this->params()->fromPost('importar', '');
            if ($importar != '') {
                $comunasNeiva = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
                $comunasIbague = array(1, 3);
                $comunasEspinal = array(1, 2);
                $comunasSanVicente = array(10);
                $comunasTesalia = array(1, 2, 3, 10);
                $comunasPaicol = array(10);
                $ext = 'csv';
                $archivo = 'ARCHIVO_MIGRACION.' . $ext;
                $file = fopen($this->rutaArchivos . '/' . $archivo, "r");
                $fila = 1;

                //------------------------------------------------------
                $fechahorareg = date('Y-m-d H:i:s');
                //------------------------------------------------------
                $idCliente = $this->getAutoincrement('cliente');
                $insertClientes = array();
                //------------------------------------------------------
                $idDireccion = $this->getAutoincrement('direcciones');
                $insertDirecciones = array();
                //------------------------------------------------------
                $insertClienteDireccion = array();
                //------------------------------------------------------
                $idServicio = $this->getAutoincrement('servicio');
                $insertServicios = array();
                //------------------------------------------------------
                $idInternet = $this->getAutoincrement('internet');
                $insertInternet = array();
                //------------------------------------------------------
                $idTelevision = $this->getAutoincrement('television');
                $insertTelevision = array();
                //------------------------------------------------------
                $idCobro = $this->getAutoincrement('cobros');
                $insertCobros = array();
                //------------------------------------------------------
                $idPagoAux = $this->getAutoincrement('pagos_aux');
                $insertPagos = array();
                //------------------------------------------------------

                while (($datos = fgetcsv($file, 0, ';')) == true) {
                    $codigo = trim($datos[0]);
                    $razonsocial = trim($datos[1]);
                    $apellidos = trim($datos[2]);
                    $nombres = trim($datos[3]);
                    $estado = trim($datos[4]);
                    $tipopersona = trim($datos[5]);
                    $identificacion = trim($datos[6]);
                    $tipovivienda = trim($datos[7]);
                    $comuna = trim($datos[8]);
                    $barrio = trim($datos[9]);
                    $direccion = trim($datos[10]);
                    $telefono = trim($datos[11]);
                    $email = trim($datos[12]);
                    $celular = trim($datos[13]);
                    $osinstall = trim($datos[14]);
                    $fechainstall = trim($datos[15]);
                    $tarifa = trim($datos[16]);
                    $vlrtarifa = $this->limpiarPuntosComas(trim($datos[17]));
                    $numtvppal = trim($datos[18]);
                    $numtvadd = trim($datos[19]);
                    //--------------------------------------------------
                    $deuda = $this->limpiarPuntosComas(trim($datos[20]));
                    $fechacorte = trim($datos[21]);
                    $estrato = trim(substr($datos[22], 7));
                    $fecharegistro = trim($datos[23]);
                    $username = trim($datos[24]);
                    $password = trim($datos[25]);
                    $serial = trim($datos[26]);
                    $clasificacion = trim($datos[27]);
                    $sucursal = trim($datos[28]);
                    $nuncomuna = trim(substr($comuna, 6));
                    //--------------------------------------------------
                    if ($nombres == '') {
                        $nombres = 'N.N';
                    }
//******************************************************************************
//******************************************************************************

                    switch ($sucursal) {
                        case 'NEIVA':
                            $idSucursal = 1;
                            $idMunicipio = 605;
                            $vlrbasicodefault = 27000;
                            break;
                        case 'IBAGUE':
                            $idSucursal = 2;
                            $idMunicipio = 959;
                            $vlrbasicodefault = 24000;
                            break;
                        case 'ESPINAL':
                            $idSucursal = 6;
                            $idMunicipio = 974;
                            $vlrbasicodefault = 30000;
                            break;
                        case 'SAN VICENTE':
                            $idSucursal = 5;
                            $idMunicipio = 358;
                            $vlrbasicodefault = 30000;
                            break;
                        case 'TESALIA':
                            $idSucursal = 3;
                            $idMunicipio = 636;
                            $vlrbasicodefault = 21000;
                            break;
                        case 'PAICOL':
                            $idSucursal = 4;
                            $idMunicipio = 625;
                            $vlrbasicodefault = 22000;
                            break;
                    }
                    switch ($tipopersona) {
                        case 'P. NATURAL':
                            $idTipoIdentificacion = 1;
                            $tipocliente = 'Persona Natural';
                            break;
                        case 'P.JURIDICA':
                            $idTipoIdentificacion = 2;
                            $tipocliente = 'Persona Juridica';
                            break;
                    }
                    switch ($tipovivienda) {
                        case 'CASA PROPIA':
                            $idTipoVivienda = 1;
                            break;
                        case 'CASA ALQUILADA':
                            $idTipoVivienda = 2;
                            break;
                        case 'EDIFICIO':
                            $idTipoVivienda = 3;
                            break;
                        case 'HOSTAL / HOTEL':
                            $idTipoVivienda = 4;
                            break;
                        case 'TIENDA':
                            $idTipoVivienda = 5;
                            break;
                        case 'INSTITUCIONES':
                            $idTipoVivienda = 6;
                            break;
                        case 'INSTITUCION':
                            $idTipoVivienda = 6;
                            break;
                        case 'FAMILIAR':
                            $idTipoVivienda = 7;
                            break;
                    }
                    if ((strpos($tarifa, 'BASICO') !== false && strpos($tarifa, 'MBPS') === false) || strpos($tarifa, 'TV GPON') !== false || strpos($tarifa, 'TV EMPLEADOS') !== false || (strpos($tarifa, 'CORTESIA') !== false && strpos($tarifa, 'INTERNET') === false) || strpos($tarifa, 'ESAL') !== false || strpos($tarifa, 'BASICA ESPECIAL') !== false) {
                        $idTipoServicio = 2;
                    } else {
                        $idTipoServicio = 1;
                    }
                    if ($numtvppal == 0) {
                        $basico = 1;
                    } else {
                        $basico = $numtvppal;
                    }
                    $partesfechainstall = explode('/', $fechainstall);
                    if (count($partesfechainstall) > 2) {
                        $fechainstalacion = $partesfechainstall[2] . '-' . $partesfechainstall[1] . '-' . $partesfechainstall[0];
                    } else {
                        $fechainstalacion = $fechainstall;
                    }
                    $partesfecharegistro = explode('/', $fecharegistro);
                    if (count($partesfecharegistro) > 2) {
                        $fechahoraregServicio = $partesfecharegistro[2] . '-' . $partesfecharegistro[1] . '-' . $partesfecharegistro[0];
                    } else {
                        $fechahoraregServicio = $fecharegistro;
                    }
                    $partesfechacorte = explode('/', $fechacorte);
                    if (count($partesfechacorte) > 2) {
                        $fechaultcorte = $partesfechacorte[2] . '-' . $partesfechacorte[1] . '-' . $partesfechacorte[0];
                    } else {
                        $fechaultcorte = $fechacorte;
                    }
                    if ($clasificacion == 'BLUE') {
                        $numclasificacion = 1;
                    } else {
                        $numclasificacion = 2;
                    }
                    $estadoServicio = ucfirst($estado);

                    //--------------------------------------------------
                    $clienteArray = $this->getMigracionDAO()->getClienteByIdentificacion();
                    if (is_array($clienteArray)) {
                        $idClienteAux = $clienteArray['idCliente'];
                    } else {
//                        $insertClientes[] = "INSERT INTO `cliente` (`idCliente`, `idTipoIdentificacion`, `tipocliente`, `identificacion`, `nombres`, `apellidos`, `razonsocial`, `telefono`, `celular1`, `celular2`, `celular3`, `emailcontacto`, `emailfacturacion`, `sexo`, `representantelegal`, `identificacionreprelegal`, `estado`, `registradopor`, `modificadopor`, `fechahorareg`, `fechahoramod`, `clasificacion`) VALUES ($idCliente, $idTipoIdentificacion, '$tipocliente', '$identificacion', '$nombres', '$apellidos', '$razonsocial', '$telefono', '$celular', '', '', '$email', '', 'Masculino', '', '', 'Registrado', 'Sistema', '', '$fechahorareg', '0000-00-00 00:00:00', '$clasificacion')";
                        $insertClientes[] = "$idCliente,$idTipoIdentificacion,$tipocliente,$identificacion,$nombres,$apellidos,$razonsocial,$telefono,$celular,,,$email,,Masculino,,,Registrado,Sistema,,$fechahorareg,0000-00-00 00:00:00,$numclasificacion";
                        $idClienteAux = $idCliente;
                    }
                    //--------------------------------------------------
                    $barrioArray = $this->getMigracionDAO()->getBarrio($idSucursal, $nuncomuna, $barrio);
                    if (is_array($barrioArray)) {
                        $idBarrio = $barrioArray['idBarrio'];
                    } else {
                        $idBarrio = 1;
                        $errores[] = "[$fila] BARRIO NO DEFINIDO";
                    }
                    $insertDirecciones[] = "$idDireccion,$idBarrio,$idTipoVivienda,$idMunicipio,$direccion,0,0,Sistema,,$fechahorareg,0000-00-00 00:00:00";
                    $insertClienteDireccion[] = "$idClienteAux,$idDireccion,Residencia";
                    //--------------------------------------------------
                    if (strpos($tarifa, 'BASICO') !== false && strpos($tarifa, 'MBPS') === false && strpos($tarifa, 'BASICO 20 T') === false && strpos($tarifa, 'BASICO 23 T') === false) {
                        $partesTarifa = explode(' ', $tarifa);
                        if (count($partesTarifa) != 2) {
                            $errores[] = "[$fila] TARIFA BASICO SIN BASE ESPECIFICADA";
                        } else {
                            $tarifa = 'BASICO';
                            $basico = $partesTarifa[1];
                            $tarifaArray = $this->getMigracionDAO()->getTarifa($idSucursal, $idTipoServicio, $tarifa, $vlrbasicodefault);
                        }
                    } else {
                        if (strpos($tarifa, 'TV GPON') !== false) {
                            $tarifa = 'TV GPON';
                            $tarifaArray = $this->getMigracionDAO()->getTarifa($idSucursal, $idTipoServicio, $tarifa, $vlrbasicodefault);
                        } else {
                            $tarifaArray = $this->getMigracionDAO()->getTarifa($idSucursal, $idTipoServicio, $tarifa, $vlrtarifa);
                        }
                    }
                    if (is_array($tarifaArray)) {
                        $idTarifa = $tarifaArray['idTarifa'];
                        $unidadanchobanda = $tarifaArray['unidadanchobanda'];
                        $velsubida = $tarifaArray['velsubida'];
                        $velbajada = $tarifaArray['velbajada'];
                    } else {
                        $idTarifa = 0;
                        $unidadanchobanda = 'Mbps';
                        $velsubida = 1;
                        $velbajada = 1;
                        $errores[] = "[$fila] TARIFA NO DEFINIDA";
                    }
//                    $insertServicios[] = "INSERT INTO `servicio` (`idServicio`, `idTipoServicio`, `idSucursal`, `idCliente`, `idTarifa`, `idDireccion`, `estrato`, `conceptofacturacion`, `tarifa`, `basico`, `fechainstalacion`, `pagoinstalacion`, `diacorte`, `observacion`, `soportelegal`, `numtvsprincipales`, `numtvadicionales`, `numlegalizados`, `prorrateook`, `contmesgratis`, `instalacionok`, `estado`, `registradopor`, `legalizadopor`, `modificadopor`, `fechahorareg`, `fechahoralegal`, `fechahoramod`, `fechaultcorte`, `fecharetiro`, `codanterior`, `clasificacion`) VALUES ($idServicio, $idTipoServicio, $idSucursal, $idClienteAux, $idTarifa, $idDireccion, $estrato, '$tarifa', $vlrtarifa, $basico, '$fechainstalacion', 0, 2, '', '', $numtvppal, $numtvadd, 0, 1, 0, 1, 'Registrado', 'Sistema', 'Sistema', '', '$fechahoraregServicio', '$fechahoraregServicio', '0000-00-00 00:00:00', '$fechaultcorte', '0000-00-00', '$codigo', $numclasificacion)";
                    $insertServicios[] = "$idServicio,$idTipoServicio,$idSucursal,$idClienteAux,$idTarifa,$idDireccion,$estrato,$tarifa,$vlrtarifa,$basico,$fechainstalacion,0,2,,,$numtvppal,$numtvadd,0,1,0,1,$estadoServicio,Sistema,Sistema,,$fechahoraregServicio,$fechahoraregServicio,0000-00-00 00:00:00,$fechaultcorte,0000-00-00,,$codigo,$numclasificacion";
                    //--------------------------------------------------
                    if ($idTipoServicio == 1) {
                        $insertInternet[] = "$idInternet,$idServicio,0,$unidadanchobanda,$velsubida,$velbajada,$username,$password";
                        $idInternet++;
                    } else {
                        $insertTelevision[] = "$idTelevision,$idServicio";
                        $idTelevision++;
                    }
                    //--------------------------------------------------
                    if ($deuda > 0) {
                        $insertCobros[] = "$idCobro,NULL,$idServicio,Deuda,SALDO ANTERIOR,$deuda,0,$deuda,$deuda,1,2019,Sistema,,$fechahorareg,0000-00-00 00:00:00";
                        $idCobro++;
                    }
                    if ($deuda < 0) {
                        $pagoaux = intval($deuda * (-1));
                        $insertPagos[] = "$idPagoAux,$idServicio,,$pagoaux";
                        $idPagoAux++;
                    }
                    //--------------------------------------------------
                    if ($idClienteAux == $idCliente) {
                        $idCliente++;
                    }
                    $idDireccion++;
                    $idServicio++;
                    //--------------------------------------------------
                    $fila++;
                }
                fclose($file);
                //**************************************************************
                //**************************************************************
                $this->escribirEnDisco('INSERT_CLIENTES.csv', $insertClientes);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_DIRECCIONES.csv', $insertDirecciones);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_CLIENTE_DIRECCION.csv', $insertClienteDireccion);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_SERVICIOS.csv', $insertServicios);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_INTERNET.csv', $insertInternet);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_TELEVISION.csv', $insertTelevision);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_COBROS.csv', $insertCobros);
                //--------------------------------------------------------------
                $this->escribirEnDisco('INSERT_PAGOS.csv', $insertPagos);
                //**************************************************************
                //**************************************************************
                $this->importarBD('INSERT_CLIENTES.csv', 'cliente');
                $this->importarBD('INSERT_DIRECCIONES.csv', 'direcciones');
                $this->importarBD('INSERT_CLIENTE_DIRECCION.csv', 'cliente_direccion');
                $this->importarBD('INSERT_SERVICIOS.csv', 'servicio');
                if (count($insertInternet) > 0) {
                    $this->importarBD('INSERT_INTERNET.csv', 'internet');
                }
                if (count($insertTelevision) > 0) {
                    $this->importarBD('INSERT_TELEVISION.csv', 'television');
                }
                $this->importarBD('INSERT_COBROS.csv', 'cobros');
                $this->importarBD('INSERT_PAGOS.csv', 'pagos_aux');
            }
        }
        $jsonModel = new \Zend\View\Model\JsonModel();
        $jsonModel->setVariables(array(
            'contImportados' => count($insertServicios),
            'contErrores' => count($errores),
            'errores' => $errores,
        ));
        return $jsonModel;
    }

//------------------------------------------------------------------------------    

    private function escribirEnDisco($archivo = '', $inserts = array()) {
        $insertsTXT = "";
//        $fileEscribir = fopen('D:/xampp/mysql/data/alpa_bd/' . $archivo, "w");
        $fileEscribir = fopen('/var/lib/mysql-files/' . $archivo, "w");
        foreach ($inserts as $insert) {
            $insertsTXT .= $insert . "\n";
        }
        fwrite($fileEscribir, $insertsTXT);
        fclose($fileEscribir);
    }

//------------------------------------------------------------------------------    

    private function importarBD($archivo = '', $tabla = '') {
//        $sql = "LOAD DATA LOCAL INFILE '$archivo'
        $sql = "LOAD DATA LOCAL INFILE '/var/lib/mysql-files/$archivo'
                INTO TABLE $tabla
                FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '" . '"' . "' 
                LINES TERMINATED BY '\n'";
        try {
            $dbh = new \PDO('mysql:host=localhost;dbname=alpa_bd', 'root', 'josandro.bd', array(\PDO::MYSQL_ATTR_LOCAL_INFILE => true));
            $result = $dbh->exec($sql);
            $dbh = null;
        } catch (\PDOExceptionn $e) {
            print "Error2!: " . $e->getMessage() . "<br/>";
        }
    }

//------------------------------------------------------------------------------    

    private function getAutoincrement($tabla = '') {
        $sql = "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'alpa_bd' AND TABLE_NAME = '$tabla'";
        try {
            $dbh = new \PDO('mysql:host=localhost;dbname=alpa_bd', 'root', 'josandro.bd', array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $registros = $stmt->fetchAll();
//            print_r($registros);
            $dbh = null;
            return $registros[0]['AUTO_INCREMENT'];
        } catch (\PDOExceptionn $e) {
            print "Error2!: " . $e->getMessage() . "<br/>";
        }
    }

//------------------------------------------------------------------------------    

    public function limpiarPuntosComas($a = 0) {
//        $a = '27000';
        $a = trim($a);
//        echo $a . '<br>';
        $posPto = strpos($a, '.');
        $posComa = strpos($a, ',');
        if ($posPto !== false && $posComa !== false) {
//            echo $posPto . ' ' . $posComa . '<br>';
            if ($posPto < $posComa) {
                $a = substr($a, 0, $posComa);
            } else {
                $a = substr($a, 0, $posPto);
            }
//            echo $a . ' if<br>';
        } else {
            if ($posPto !== false) {
                if (($posPto + 3) == strlen($a)) {
                    $a = substr($a, 0, $posPto);
                }
            }
            if ($posComa !== false) {
                if (($posComa + 3) == strlen($a)) {
                    $a = substr($a, 0, $posComa);
                }
            }
        }
        $a = str_replace('.', '', $a);
        $a = str_replace(',', '', $a);
        return $a;
    }

//------------------------------------------------------------------------------    

    public function verventassinentregarAction() {
        $session = $this->getInfoSesionUsuario();
        $cobros = $this->getCobroDAO()->getCobrosInstalacionByIdEmpleado($session['idEmpleado'], 0);
        $view = new ViewModel(array(
            'cobros' => $cobros,
            'infoEmpleado' => $cobros[0],
        ));
        $view->setTerminal(true);
        return $view;
    }

//------------------------------------------------------------------------------    
}
