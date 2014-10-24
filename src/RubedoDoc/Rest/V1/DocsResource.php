<?php
namespace RubedoDoc\Rest\V1;

use RubedoAPI\Entities\API\Definition\FilterDefinitionEntity;
use RubedoAPI\Entities\API\Definition\VerbDefinitionEntity;
use RubedoAPI\Rest\V1\AbstractResource;

/**
 * Class DocsResource
 * @package RubedoDoc\Rest\V1
 * @method \RubedoDoc\Collection\Documentation getDocumentationAPICollection() Return Documentation collection
 */
class DocsResource extends AbstractResource {
    function __construct()
    {
        parent::__construct();
        $this->define();
    }

    public function getAction($params)
    {
        $documentationList = $this->getDocumentationAPICollection()->getList()['data'];
        $docs = array();
        $namespacesToSearch = array();
        foreach ($this->getContext()->getServiceLocator()->get('ModuleManager')->getLoadedModules() as $module) {
            $moduleConfig = $module->getConfig();
            $namespacesToSearch = array_merge($namespacesToSearch, isset($moduleConfig['namespaces_api'])?$moduleConfig['namespaces_api']:array());
        }

        foreach($documentationList as $docEntry) {
            $doc = array(
                'url' => $docEntry['url'],
            );
            list($version, $route) = $this->splitAndSanitizeURL($docEntry['url']);
            $resourceArray = array('Rest', $version);
            $class = ucfirst(array_pop($route)) . 'Resource';
            if (!empty($route)) {
                $resourceArray = array_merge($resourceArray, $route);
            }
            $resourceArray[] = $class;
            foreach(array_reverse($namespacesToSearch) as $namespaceWithRest) {
                /** @var \RubedoAPI\Interfaces\IResource $resourceObject */
                $namespacedResource = implode('\\', array_merge(array($namespaceWithRest), $resourceArray));
                if (class_exists($namespacedResource)) {
                    $resourceObject = new $namespacedResource();
                    break;
                }
            }
            if (!isset($resourceObject)) {
                continue;
            }
            $options = $resourceObject->optionsAction();
            if (!empty($options['name'])) {
                if (!empty($docEntry['codeExamples'])) {
                    foreach ($options['verbs'] as $verb => &$verbData) {
                        if (isset($docEntry['codeExamples'][strtolower($verb)])) {
                            $verbData['codeExamples'] = $docEntry['codeExamples'][strtolower($verb)];
                        }
                    }
                }
                $doc['options'] = $options;
            }
            $optionsEntity = $resourceObject->optionsEntityAction();
            if (!empty($optionsEntity['name'])) {
                if (!empty($docEntry['codeExamplesEntity'])) {
                    foreach ($optionsEntity['verbs'] as $verb => &$verbData) {
                        if (isset($docEntry['codeExamplesEntity'][strtolower($verb)])) {
                            $verbData['codeExamples'] = $docEntry['codeExamplesEntity'][strtolower($verb)];
                        }
                    }
                }
                $doc['optionsEntity'] = $optionsEntity;
            }
            $docs[] = $doc;
        }
        return array(
            'success' => true,
            'docs' => $docs,
        );
    }

    protected function splitAndSanitizeURL($url) {
        $explodedUrl = explode('/', $url);
        foreach($explodedUrl as $i => $segment) {
            if (empty($segment) || in_array($segment, array('/', 'api'))) {
                unset($explodedUrl[$i]);
            } else {
                $explodedUrl[$i] = ucfirst($explodedUrl[$i]);
            }
        }
        return array(mb_strtoupper(array_shift($explodedUrl)), $explodedUrl);
    }
    protected function define()
    {
        $this
            ->definition
            ->setName('Docs')
            ->setDescription('Rubedo docs for your website ')
            ->editVerb('get', function(VerbDefinitionEntity &$entity) {
                $entity
                    ->setDescription('Get full documentation')
                    ->addOutputFilter(
                        (new FilterDefinitionEntity())
                            ->setDescription('Documentation entries')
                            ->setKey('docs')
                            ->setRequired()
                    );
            });
    }
} 