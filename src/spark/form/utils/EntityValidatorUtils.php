<?php

namespace spark\form\utils;

use spark\form\validator\ArrayEntityValidator;
use spark\form\validator\EntityValidator;
use spark\form\validator\JoinedEntityValidator;
use spark\utils\Collections;
use spark\utils\Objects;

class EntityValidatorUtils {


    /**
     *
     *  join (array(
     *      new AnnotationValidator(),
     *      new UserEntityValidator(),
     *      array(
     *          "user.login"=>array(
     *              new MailValidator(...),
     *              new UniqueValidator(...)
     *          ),
     *          "user.name"=>new UniqueValidator(...),
     *      )
     * ) )
     *
     *
     * @param array $array
     * @return EntityValidator
     */
    public static function joined($array = array()) {

        $entityValidators = Collections::builder()
            ->addAll($array)
            ->map(function ($x) {
                if (Objects::isArray($x)) {
                    return new ArrayEntityValidator($x);
                } else {
                    return $x;
                }
            })->get();

        return new JoinedEntityValidator($entityValidators);
    }
}