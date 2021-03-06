<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\NotNull;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class NotNullAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return NotNull::class;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return Objects::isNotNull($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}