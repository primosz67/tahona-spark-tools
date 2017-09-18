<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.11.16
 * Time: 16:19
 */

namespace Spark\Form;


use Spark\Utils\Asserts;
use Spark\Utils\Collections;

class ErrorsHolder {

    private $errors = array();

    public function addError($fieldPathParam, array $errors) {
        Asserts::notNull($fieldPathParam);
        Asserts::checkArgument(Collections::isNotEmpty($errors), "Errors should not be empty.");

        $this->errors[$fieldPathParam] = Collections::stream()
            ->addAll(Collections::getValueOrDefault($this->errors, $fieldPathParam, array()))
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
        $this->errors = Collections::stream()
            ->addAll($this->errors)
            ->addAll($errors)
            ->get();
    }


}