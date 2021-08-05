<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuarios;

return array(
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Usuarios\Controller',
                        'controller' => 'Login',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action][/:id1][/:id2]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id1' => '[a-zA-Z0-9_-]*',
                                'id2' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'usuarios' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/usuarios',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Usuarios\Controller',
                        'controller' => 'Usuarios',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action][/:id1][/:id2]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id1' => '[a-zA-Z0-9_-]*',
                                'id2' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Usuarios\Controller\Login' => 'Usuarios\Controller\LoginController',
            'Usuarios\Controller\Roles' => 'Usuarios\Controller\RolesController',
            'Usuarios\Controller\Recursos' => 'Usuarios\Controller\RecursosController',
            'Usuarios\Controller\Acciones' => 'Usuarios\Controller\AccionesController',
            'Usuarios\Controller\Privilegios' => 'Usuarios\Controller\PrivilegiosController',
            'Usuarios\Controller\Usuarios' => 'Usuarios\Controller\UsuariosController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
        'aliases' => array(// !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'servicioAutenticacion',
        ),
        'invokables' => array(
            'servicioAutenticacion' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);
