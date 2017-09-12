<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 07:05
 */

namespace Spark\Form\Validator;


use Spark\Common\IllegalArgumentException;
use Spark\Common\Optional;
use Spark\Form\Validator;
use Spark\Utils\Collections;
use Spark\Utils\Functions;
use Spark\Utils\Objects;
use Spark\Utils\Predicates;

abstract class EntityValidator {

    abstract public function validateFieldValue($validatorKey, $obj, $field, $value);

    public function checkFieldExist($data = array()) {
        return array();
    }
}