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

class EmailAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "Spark\\form\\validator\\annotation\\Email";
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