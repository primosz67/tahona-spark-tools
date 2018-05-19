<?php
/**
 * Date: 19.05.18
 * Time: 15:00
 */

namespace Spark\Form\Validator;


use Spark\Form\Validator;

class BooleanValidator extends Validator {

    private $validBooleanValue;

    public function __construct($validBooleanValue, $message) {
        $this->validBooleanValue = $validBooleanValue;
        parent::__construct($message);
    }

    public function isValid($value) {
        return $value === $this->validBooleanValue;
    }
}