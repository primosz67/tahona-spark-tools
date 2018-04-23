<?php
/**
 *
 *
 * Date: 2018-03-16
 * Time: 07:39
 */

namespace Spark\Form;

use ReflectionProperty;
use Spark\Form\Validator\ArrayEntityValidator;
use Spark\Form\Validator\EntityValidator;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class ObjectValidator {

    private $objectReferences = array();
    /**
     * @var EntityValidator
     */
    private $validators;
    /**
     * @var array
     */
    private $converters = array();
    /**
     * @var ErrorsHolder
     */
    private $errorsHolder;
    const SEPARATOR = '_';

    public function __construct() {
        $this->errorsHolder = new ErrorsHolder();
    }

    public function validate($object): Errors {
        $this->errorsHolder->clear();
        $this->validateObject($object);
        return $this->errorsHolder;
    }

    private function validateObject($object, string $prefix = null): void {
        $className = Objects::getClassName($object);
        $reflectionClass = new \ReflectionClass($className);
        $prop = $reflectionClass->getProperties();

        foreach ($prop as $p) {
            /** @var ReflectionProperty $p */
            $p->setAccessible(true);
            $value = $p->getValue($object);

            if (Objects::isPrimitive($value) || Objects::isNull($value)) {
                $errors = $this->validateField('', $object, $p->name, $value);
                $key = StringUtils::join(".", array($prefix, $p->name), true);
                $this->errorsHolder->addError($key, $errors);
            } else {
                $this->validateObject($value, $p->name);
            }
        }
    }

    private function validateField($validationKey, $obj, $field, $value): array {
        if (isset($this->validators)) {
            return $this->validators->validateFieldValue($validationKey, $obj, $field, $value);
        }
        return array();
    }

    public function setValidators($validators): void {
        if (is_array($validators)) {
            $this->validators = new ArrayEntityValidator($validators);
        } else if ($validators instanceOf EntityValidator) {
            $this->validators = $validators;
        }
    }
}