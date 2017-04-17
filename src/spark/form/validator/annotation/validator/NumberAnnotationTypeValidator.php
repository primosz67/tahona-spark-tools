<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace spark\form\validator\annotation\validator;


use spark\utils\MathUtils;
use spark\utils\Objects;
use spark\utils\StringUtils;
use spark\utils\ValidatorUtils;

class NumberAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\Number";
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || MathUtils::isNumeric($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}