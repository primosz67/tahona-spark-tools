<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 07:05
 */

namespace spark\form\validator;


use spark\common\IllegalArgumentException;
use spark\common\Optional;
use spark\form\Validator;
use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;

abstract class EntityValidator {

    abstract public function validateFieldValue($validatorKey, $obj, $field, $value);

    public function checkFieldExist($data = array()) {
        return array();
    }
}