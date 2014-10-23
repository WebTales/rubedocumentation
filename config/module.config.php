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
    'blocksDefinition' => array(
        'docApi' => array(
            'maxlifeTime' => 60,
            'definitionFile' => realpath(__DIR__ . "/blocks/") . '/docApi.json'
        )
    ),
    'extension_paths' => array(
        'rubedocumentation' => array(
            'path' => realpath(__DIR__ . '/../block/docApi'),
            'css' => array('css/docApi.css'),
            'js' => array('js/docApi.js'),
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
