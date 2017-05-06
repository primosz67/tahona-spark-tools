<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace spark\form\validator\annotation\validator;


use spark\utils\Objects;
use spark\utils\StringUtils;
use spark\utils\ValidatorUtils;

class MinAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\Min";
    }

    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || $value >= $annotation->value;
    }

    public function getAnnotationValues($annotation) {
        return array($annotation->value);
    }
}