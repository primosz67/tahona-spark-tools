<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\RegEx;
use Spark\Utils\StringUtils;

class RegExAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return RegEx::class;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || StringUtils::matches($obj);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}