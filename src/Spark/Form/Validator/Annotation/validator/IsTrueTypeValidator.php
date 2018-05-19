<?php
/**
 * Date: 18.05.18
 * Time: 14:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\IsTrue;
use Spark\Utils\BooleanUtils;

class IsTrueTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return IsTrue::class;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return BooleanUtils::isTrue($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}