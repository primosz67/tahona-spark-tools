<?php
/**
 *
 *
 * Date: 17.04.17
 * Time: 10:28
 */

namespace Spark\Form\Validator\Annotation\validator;


use Spark\Form\Validator\Annotation\ZipCode;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\ValidatorUtils;

class ZipCodeAnnotationTypeValidator implements AnnotationTypeValidator {


    public function getAnnotationClassName() {
        return ZipCode::class;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function isValid($obj, $value, $annotation) {
        return StringUtils::isBlank($value) || ValidatorUtils::isZipCodeValid($value);
    }


    public function getAnnotationValues($annotation) {
        return array();
    }
}