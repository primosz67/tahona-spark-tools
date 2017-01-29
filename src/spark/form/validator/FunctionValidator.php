<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.03.15
 * Time: 21:31
 */

namespace spark\form\validator;


use spark\form\Validator;

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