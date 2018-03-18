<?php
/**
 *
 *
 * Date: 12.03.15
 * Time: 21:31
 */

namespace Spark\Form\Validator;


use Spark\Form\Validator;

class FunctionValidator extends Validator {

    private $func;

    function __construct($errorMessage, \Closure $func) {
        parent::__construct($errorMessage);
        $this->func = $func;
    }

    public function isValid($value) {
        $func = $this->func;
        return $func(($value));
    }
}