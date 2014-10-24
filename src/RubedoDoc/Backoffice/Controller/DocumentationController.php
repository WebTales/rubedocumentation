<?php

namespace RubedoDoc\Backoffice\Controller;

use Rubedo\Backoffice\Controller\DataAccessController;
use Rubedo\Services\Manager;

class DocumentationController extends DataAccessController
{
    public function __construct()
    {
        parent::__construct();
        $this->_dataService = Manager::getService('Documentation');
    }
}