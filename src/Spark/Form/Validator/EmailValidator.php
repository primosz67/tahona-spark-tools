<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 07.04.15
 * Time: 23:11
 */

namespace Spark\Form\Validator;


use Spark\Form\Validator;
use Spark\Utils\ValidatorUtils;

class EmailValidator extends Validator {

    public function isValid($value) {
      return ValidatorUtils::isMailValid($value);
    }
}