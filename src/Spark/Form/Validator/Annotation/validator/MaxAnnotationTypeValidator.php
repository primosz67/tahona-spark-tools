<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\Max;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

class MaxAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return Max::class;
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || $value <= $annotation->value;
    }

    public function getAnnotationValues($annotation) {
        return array($annotation->value);
    }
}