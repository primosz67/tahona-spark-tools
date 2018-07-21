<?php
/**
 *
 *
 * Date: 27.11.16
 * Time: 16:19
 */

namespace Spark\Form;

use Spark\Utils\Asserts;
use Spark\Utils\Collections;

class ErrorsHolder implements Errors {

    private $errors = array();

    public function addError($fieldPathParam, array $errors = []) {
        Asserts::notNull($fieldPathParam);

        if (Collections::isNotEmpty($errors)) {
            $this->errors[$fieldPathParam] = Collections::stream()
                ->addAll(Collections::getValueOrDefault($this->errors, $fieldPathParam, array()))
                ->addAll($errors)
                ->get();
        }
    }

    public function clear() {
        $this->errors = array();
    }

    public function getErrors() :array {
        return $this->errors;
    }

    public function addAllError($errors) {
        $this->errors = Collections::stream()
            ->addAll($this->errors)
            ->addAll($errors)
            ->get();
    }

    public function hasErrors(): bool {
        return Collections::isNotEmpty($this->errors);
    }
}