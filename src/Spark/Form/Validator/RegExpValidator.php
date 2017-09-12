<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 05:01
 */

namespace Spark\Form\Validator;


use Spark\Form\Validator;

class RegExpValidator extends Validator {

    private $regExp;

    public function __construct($regExp, $errorMessage) {
        parent::__construct($errorMessage);
        $this->regExp = $regExp;
    }

    public function isValid($value) {
        return preg_match($this->regExp, $value) === 1;
    }
}