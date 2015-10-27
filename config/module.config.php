<?php
return array(
    'namespaces_api' => array(
        'RubedoDoc',
    ),
    'appExtension' => array(
        'rubedodocumentation' => array(
            'basePath' => realpath(__DIR__ . '/../app-extension') . '/rubedodocumentation',
            'definitionFile' => realpath(__DIR__ . '/../app-extension') . '/rubedodocumentation.json'
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'RubedoDoc\\Collection\\Documentation' => 'RubedoDoc\\Collection\\Documentation',
        ),
        'aliases' => array(
            'API\\Collection\\Documentation' => 'RubedoDoc\\Collection\\Documentation',
            'Documentation' => 'RubedoDoc\\Collection\\Documentation',
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
            'css' => array('css/docApi.css','//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.3/styles/default.min.css'),
            'js' => array('js/docApi.js','//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.3/highlight.min.js'),
            'angularModules' => array('hljs' => 'src/library/angular-highlightjs.min.js')
        ),
    ),
    'router' => array (
        'routes' => array(
            'rubedo-documentation' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/backoffice/rubedo-documentation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'RubedoDoc\\Backoffice\\Controller',
                        'controller' => 'documentation',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:action]',
                            '__NAMESPACE__' => 'RubedoDoc\\Backoffice\\Controller',
                            'constraints' => array(
                                'controller' => 'documentation',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'RubedoDoc\\Backoffice\\Controller\\Documentation' => 'RubedoDoc\\Backoffice\\Controller\\DocumentationController'
        ),
    ),
);
