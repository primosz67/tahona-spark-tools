<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\NotBlank;
use Spark\Utils\StringUtils;

class NotBlankAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return NotBlank::class;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function isValid($obj, $value, $annotation) {
        return StringUtils::isNotBlank($value);
    }

    public function getAnnotationValues($annotation) {
        return array();
    }
}