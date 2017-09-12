<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

class DateAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "Spark\\form\\validator\\annotation\\Date";
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || ValidatorUtils::isDate($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}