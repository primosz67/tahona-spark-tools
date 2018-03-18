<?php
/**
 *
 *
 * Date: 01.02.17
 * Time: 21:38
 */

namespace Spark\Form\Validator;


use Spark\Utils\Collections;
use Spark\Utils\Functions;
use Spark\Utils\Objects;
use Spark\Utils\Predicates;

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
            ->flatMap(Functions::getSameObject(), true)
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