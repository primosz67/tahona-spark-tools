<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 04:55
 */

namespace spark\form\validator;


use spark\form\Validator;

class NotEmptyValidator extends Validator {

    public function isValid($value) {
        return false === empty($value);
    }
}