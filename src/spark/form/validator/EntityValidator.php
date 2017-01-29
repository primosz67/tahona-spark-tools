<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 12.07.14
 * Time: 07:05
 */

namespace spark\form\validator;


use spark\common\IllegalArgumentException;
use spark\common\Optional;
use spark\form\Validator;
use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;

class EntityValidator {

    private $validators = array();

    function __construct($validators) {
        $this->validators = $validators;
    }

    /**
     * @param mixed $validators
     */
    public function setValidators($validators) {
        $this->validators = $validators;
    }

    public function validateFieldValue($validatorKey, $obj, $field, $value) {
        if (isset ($this->validators) && isset($this->validators[$validatorKey])) {
            /** @var $validator Validator */
            $validator = $this->validators[$validatorKey];

            if (Objects::isArray($validator)) {
                return Collections::builder($validator)
                    ->map(function($validator) use ($obj, $field, $value){
                        return $this->validate($obj, $field, $value, $validator);
                    })
                    ->filter(Predicates::notNull())
                    ->get();
            } else {

                return Collections::builder(array($this->validate($obj, $field, $value, $validator)))
                    ->filter(Predicates::notNull())
                    ->get();
            }
        }
        return array();
    }

    /**
     * @param $obj
     * @param $field
     * @param $value
     * @param $validator Validator
     * @return string
     */
    private function validate($obj, $field, $value, $validator) {
        $validator->setFieldName($field);
        $validator->setObject($obj);

        if (false == $validator->isValid($value)) {
            return $validator->geErrorMessage();
        }
        return null;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    public function checkFieldExist($data = array()) {
        $errors = array();
        foreach(Collections::getKeys($this->validators) as $key) {
            if (!Collections::hasKey($data, $key)) {
                $validator = $this->validators[$key];
                Collections::addAllAndGroup($errors, array($key => $this->getErrorMessage($validator)));
            }
        }
        return $errors;
    }

    /**
     * @param $field
     * @param $validator
     */
    private function getErrorMessage($validator) {
        /** @var $validator Validator */
        return $validator->geErrorMessage();
    }


}