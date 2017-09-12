<?php

namespace Spark\Form\Utils;

use Spark\Form\Validator\ArrayEntityValidator;
use Spark\Form\Validator\EntityValidator;
use Spark\Form\Validator\JoinedEntityValidator;
use Spark\Utils\Collections;
use Spark\Utils\Objects;

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