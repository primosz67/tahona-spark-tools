<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 06:05
 */

namespace Spark\Form\Validator;


use Spark\Form\Validator;
use Spark\Persistence\Crud\CrudService;

class UniqueValidator extends Validator {

    /**
     * @var CrudService
     */
    private $crudService;

    function __construct($crudService, $errorMessage) {
        parent::__construct($errorMessage);
        $this->crudService = $crudService;
    }


    public function isValid($value) {
        $entities = $this->crudService->findByExample(array($this->getFieldName() => $value));
        $count = count($entities);
        if ($count == 0) {
            return true;
        } else if ($count == 1) {
            return $entities[0] === $this->getObject();

        } else {
            return false;
        }
    }
}