<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\Email;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

class EmailAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return Email::class;
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