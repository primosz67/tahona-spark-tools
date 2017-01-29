<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 06:05
 */

namespace spark\form\validator;


use spark\form\Validator;
use spark\persistence\crud\CrudService;

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
            //Experimental
            $entity = $entities[0];
            $id = $entity->getId();
            $old = $this->getObject()->getId();
            
            return $id == $old;

        } else {
            return false;
        }
    }
}