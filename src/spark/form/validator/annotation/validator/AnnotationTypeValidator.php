<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:25
 */

namespace spark\form\validator\annotation\validator;


interface AnnotationTypeValidator {

    /**
     *
     * @return string
     */
    public function getAnnotationClassName();

    /**
     *
     * @param $obj
     * @param $value
     * @param $annotation (Annotation)
     * @return boolean
     */
    public function isValid($obj, $value, $annotation);

    /**
     *  Should return Validation values for messaging.
     *  array($annotation->min, $annotation->max)
     *
     * @param $annotation
     * @return array
     */
    public function getAnnotationValues($annotation);

}