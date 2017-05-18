<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.04.17
 * Time: 10:28
 */

namespace spark\form\validator\annotation\validator;


use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\Predicates;
use spark\utils\StringUtils;
use spark\utils\ValidatorUtils;

class SizeAnnotationTypeValidator implements AnnotationTypeValidator {

    public function getAnnotationClassName() {
        return "spark\\form\\validator\\annotation\\Size";
    }

    public function isValid($obj, $value, $annotation) {

        return StringUtils::isBlank($value)
        || $this->isSizeValueValid($value, $annotation);
    }

    public function getAnnotationValues($annotation) {
        return Collections::builder()
            ->add($annotation->min)
            ->add($annotation->max)
            ->add($annotation->size)
            ->filter(Predicates::notNull())
            ->getList();
    }

    /**
     * @param $value
     * @param $annotation
     * @return bool
     */
    public function isSizeValueValid($value, $annotation) {
        $value = $this->getValue($value);
        return
            (Objects::isNull($annotation->min ) || $value >= $annotation->min)
            && (Objects::isNull($annotation->max ) || $value <= $annotation->max)
            && (Objects::isNull($annotation->size ) || $value == $annotation->size);
    }

    private function getValue($value) {
        if (Objects::isArray($value)) {
            return Collections::size($value);
        }
        if (Objects::isString($value)) {
            return StringUtils::length($value);
        }
        return $value;
    }
}