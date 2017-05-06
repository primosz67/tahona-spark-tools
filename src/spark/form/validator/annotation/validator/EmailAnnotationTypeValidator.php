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

class EmailAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\Email";
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || ValidatorUtils::isMailValid($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}