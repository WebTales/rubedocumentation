<?php
namespace RubedoDoc\Rest\V1;

use RubedoAPI\Entities\API\Definition\FilterDefinitionEntity;
use RubedoAPI\Entities\API\Definition\VerbDefinitionEntity;
use RubedoAPI\Rest\V1\AbstractResource;

class DocsResource extends AbstractResource {
    function __construct()
    {
        parent::__construct();
        $this->define();
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