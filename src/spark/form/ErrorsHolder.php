<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.11.16
 * Time: 16:19
 */

namespace spark\form;


use spark\utils\Asserts;
use spark\utils\Collections;

class ErrorsHolder {

    private $errors = array();

    public function addError($fieldPathParam, $errors) {
        Asserts::notNull($fieldPathParam);
        Asserts::checkArgument(Collections::isNotEmpty($errors), "Errors should not be empty.");

        $this->errors[$fieldPathParam] = Collections::builder(array())
            ->addAll(Collections::getValue($this->errors, $fieldPathParam))
            ->addAll($errors)
            ->get();
    }

    public function clear() {
        $this->errors = array();
    }

    public function getErrors() {
        return $this->errors;
    }

    public function addAllError($errors) {
        $this->errors = Collections::builder()
            ->addAll($this->errors)
            ->addAll($errors)
            ->get();
    }


}