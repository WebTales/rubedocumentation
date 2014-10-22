<?php
return array(
    'namespaces_api' => array(
        'RubedoDoc',
    ),
    'service_manager' => array(
        'invokables' => array(
            'RubedoDoc\\Collection\\Documentation' => 'RubedoDoc\\Collection\\Documentation',
        ),
        'aliases' => array(
            'API\\Collection\\Documentation' => 'RubedoDoc\\Collection\\Documentation',
        ),
    ),
    'router' => array (
        'routes' => array(
            'rubedo-documentation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/backoffice/rubedo-documentation[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'RubedoDoc\\Backoffice\\Controller\\Documentation',
                        'controller' => 'rubedo-documentation',
                        'action' => 'index'
                    ),
                )
            )
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'RubedoDoc\\Backoffice\\Controller\\Documentation' => 'RubedoDoc\\Backoffice\\Controller\\DocumentationController'
        ),
    ),
);
