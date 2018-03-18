<?php
/**
 *
 *
 * Date: 12.07.14
 * Time: 04:49
 */

namespace Spark\Form;


abstract class Validator {

    private $errorMessage;
    private $fieldName;
    private $object;

    function __construct($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    public abstract function isValid($value);

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     *
     * Set on Runtime binding
     * @param mixed $fieldName
     */
    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }

    /**
     * @return mixed
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object) {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getObject() {
        return $this->object;
    }



}