<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Configuracion\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Configuracion\Formularios\ZonaForm;
use Configuracion\Modelo\Entidades\Zona;

class ServidorController extends AbstractActionController {

    private $sucursalDAO;

//------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'nombresapellidos' => $sesionUsuario->nombresapellidos,
            );
        } else {
            $infoSession = array(
                'nombresapellidos' => '',
            );
        }
        return $infoSession;
    }

    public function getNombreApellidoUsuario() {
        $infoSession = $this->getInfoSesionUsuario();
        return substr(trim($infoSession['nombresapellidos']), 0, 20);
    }

//------------------------------------------------------------------------------    

    public function getSucursalDAO() {
        if (!$this->sucursalDAO) {
            $sm = $this->getServiceLocator();
            $this->sucursalDAO = $sm->get('Configuracion\Modelo\DAO\SucursalDAO');
        }
        return $this->sucursalDAO;
    }

//------------------------------------------------------------------------------

    function getFormulario($action = '', $idZona = 0) {
        $sucursales = $this->getSucursalDAO()->getSucursalesSelect();
        $selectSucursales = array();
        foreach ($sucursales as $sucursal) {
            $selectSucursales[$sucursal->getIdSucursal()] = $sucursal->getSucursal();
        }
        $form = new ZonaForm($action, $selectSucursales);
        if ($idZona != 0) {
            $zonaOBJ = $this->getZonaDAO()->getZona($idZona);
            $form->bind($zonaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function apagarAction() {
        $ok = 0;
        $clave = $this->params()->fromPost('clave', 'santelellano123*');
        if ($clave == 'santelellano123*') {
            $ok = 1;
            $pass = 'santelellano123*';
            try {
                echo shell_exec('ifconfig');
                $command = "cat $pass | su -c 'shutdown -r now'";
                shell_exec($command);
                exec($command);
                system($command);
            } catch (Exception $e) {
                print "Unable to shutdown system...\n";
                print $e;
            }
        } else {
            $ok = 0;
        }
        return new \Zend\View\Model\JsonModel(array(
            'ok' => $ok,
        ));
    }

//------------------------------------------------------------------------------    
//------------------------------------------------------------------------------
}
