<?php
/**
 *
 *
 * Date: 2018-03-16
 * Time: 07:52
 */

namespace Spark\Form;

interface Errors {

    public function getErrors(): array;

    public function hasErrors(): bool;
}