<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\Min;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

/**
 * Check numbers - value >= annotation - min value
 */
class MinAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return Min::class;
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || $value >= $annotation->value;
    }

    public function getAnnotationValues($annotation) {
        return array($annotation->value);
    }
}