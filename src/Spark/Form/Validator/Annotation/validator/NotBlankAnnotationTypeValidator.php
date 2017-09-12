<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Utils\StringUtils;

class NotBlankAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return "Spark\\form\\validator\\annotation\\NotBlank";
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