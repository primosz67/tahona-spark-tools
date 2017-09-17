<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\Number;
use Spark\Utils\MathUtils;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

class NumberAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return Number::class;
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || MathUtils::isNumeric($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}