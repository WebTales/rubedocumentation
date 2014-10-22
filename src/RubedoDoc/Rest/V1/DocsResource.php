<?php
namespace RubedoDoc\Rest\V1;

use RubedoAPI\Entities\API\Definition\FilterDefinitionEntity;
use RubedoAPI\Entities\API\Definition\VerbDefinitionEntity;
use RubedoAPI\Rest\V1\AbstractResource;

/**
 * Class DocsResource
 * @package RubedoDoc\Rest\V1
 * @method \RubedoDoc\Collection\Documentation getDocumentationCollection() Return Documentation collection
 */
class DocsResource extends AbstractResource {
    function __construct()
    {
        parent::__construct();
        $this->define();
    }

    public function getAction($params)
    {
        $this->getDocumentationCollection()->getList();
        $docs = array();
        $namespacesToSearch = array();
        foreach ($this->getContext()->getServiceLocator()->get('ModuleManager')->getLoadedModules() as $module) {
            $moduleConfig = $module->getConfig();
            $namespacesToSearch = array_merge($namespacesToSearch, isset($moduleConfig['namespaces_api'])?$moduleConfig['namespaces_api']:array());
        }

        foreach($params['url'] as $url) {
            $doc = array(
                'url' => $url,
            );
            list($version, $route) = $this->splitAndSanitizeURL($url);
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
                $doc['options'] = $options;
            }
            $optionsEntity = $resourceObject->optionsEntityAction();
            if (!empty($optionsEntity['name'])) {
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
                    ->addInputFilter(
                        (new FilterDefinitionEntity())
                            ->setDescription('List of ')
                            ->setKey('url')
                            ->setFilter('string')
                            ->setMultivalued()
                            ->setRequired()
                    )
                    ->addOutputFilter(
                        (new FilterDefinitionEntity())
                            ->setDescription('Documentation entries')
                            ->setKey('docs')
                            ->setRequired()
                    );
            });
    }
} 