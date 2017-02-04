<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 01.02.17
 * Time: 21:38
 */

namespace spark\form\validator;


use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;

class JoinedEntityValidator extends EntityValidator {
    /**
     * @var array of @EntityValidator s)
     */
    private $validators;


    /**
     * JoinedEntityValidator constructor.
     */
    public function __construct($validators = array()) {
        $this->validators = $validators;
    }

    public function checkFieldExist($data = array()) {
        return Collections::builder($this->validators)
            ->filter(Predicates::notNull())
            ->map(function ($entityValidator) use ($data) {
                /** @var EntityValidator $entityValidator */
                return $entityValidator->checkFieldExist($data);
            })
            ->flatMap(Functions::getSameObject())
            ->get();
    }


    public function validateFieldValue($validatorKey, $obj, $field, $value) {



        $result = Collections::builder($this->validators)
            ->filter(Predicates::notNull())
            ->map(function ($entityValidator) use ($validatorKey, $obj, $field, $value) {
                /** @var EntityValidator $entityValidator */


//                var_dump(Objects::getSimpleClassName($entityValidator));exit;
                return $entityValidator->validateFieldValue($validatorKey, $obj, $field, $value);
            })
            ->flatMap(Functions::getSameObject())
            ->get();


        return $result;
    }


}