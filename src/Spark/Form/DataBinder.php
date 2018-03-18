<?php
/**
 *
 *
 * Date: 17.06.14
 * Time: 00:38
 */

namespace Spark\Form;

use Spark\Common\IllegalArgumentException;
use Spark\Form\Converter\DataConverter;
use Spark\Form\Validator\ArrayEntityValidator;
use Spark\Form\Validator\EntityValidator;
use Spark\Http\Request;
use Spark\Http\Utils\RequestUtils;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class DataBinder implements Errors {

    private $postParams = array();
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
    const SEPARATOR = "_";

    /**
     * Params like $_POSt or commmon use RequestUtils::getPostParams();
     * @param $params array
     */
    public function __construct($params) {
        Asserts::notNull($params);
        $this->postParams = $params;
        $this->errorsHolder = new ErrorsHolder();
    }

    public function bind(&$object) {
        $this->errorsHolder->clear();

        $postParams = $this->filterKeys($this->postParams);
        $this->checkMissingParams($postParams);

        foreach ($postParams as $formParamKey => $value) {
            if (strpos($formParamKey, ".") > 0) {
                $accessor = explode(".", $formParamKey);
                $index = count($accessor) - 1;

                $accessObjectKey = $this->getAccessObjectKey($accessor);
                $methodName = $accessor[$index];

                if (isset($this->objectReferences[$accessObjectKey])) {
                    $objectToEdit = &$this->objectReferences[$accessObjectKey];
                } else {
                    $objectToEdit = &$object;

                    for ($i = 0; $i < $index; $i++) {
                        $propertyName = $accessor[$i];
                        if (Objects::hasMethod($objectToEdit, "get" . ucfirst($propertyName))) {
                            $newObj = Objects::invokeGetMethod($objectToEdit, $propertyName);
                            $objectToEdit = &$newObj;
                        }
                    }
                    $this->objectReferences[$accessObjectKey] = $objectToEdit;
                }

                $value = $this->convert($objectToEdit, $formParamKey, $value);
                $errors = $this->validate($formParamKey, $objectToEdit, $methodName, $value);

                if (Collections::isNotEmpty($errors)) {
                    $this->errorsHolder->addError($formParamKey, $errors);
                }
                $this->setValue($objectToEdit, $methodName, $value);

            } else {
                $objectToEdit = &$object;
                $value = $this->convert($objectToEdit, $formParamKey, $value);
                $errors = $this->validate($formParamKey, $objectToEdit, $formParamKey, $value);

                if (Collections::isNotEmpty($errors)) {
                    $this->errorsHolder->addError($formParamKey, $errors);
                }

                $this->setValue($objectToEdit, $formParamKey, $value);
            }
        }
    }

    /**
     * @param $object
     * @param $fields
     * @param $value
     */
    private function setValue(&$object, $fields, $value) {
        $methodName = "set" . ucfirst($fields);

        try {
            if (method_exists($object, $methodName)) {
                $object->$methodName($value);
            }
        } catch (\Exception $e) {
        }
    }

    private function getAccessObjectKey($accessor) {
        $size = count($accessor) - 1;

        $key = "";
        for ($i = 0; $i < $size; $i++) {
            $key .= $accessor[$i];
        }
        return $key;
    }

    private function validate($validationKey, $obj, $field, $value) {
        if (isset($this->validators)) {
            return $this->validators->validateFieldValue($validationKey, $obj, $field, $value);
        }
        return array();
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errorsHolder->getErrors();
    }


//    public function hasErrors() {
//        return Collections::isNotEmpty($this->getErrors());
//    }

    public function isValid() {
        $errors = $this->getErrors();
        return empty($errors);
    }

    public function setValidators($validators) {
        if (is_array($validators)) {
            $this->validators = new ArrayEntityValidator($validators);
        } else if ($validators instanceOf EntityValidator) {
            $this->validators = $validators;
        }
    }

    public function setDataConverters($converters = array()) {
        Asserts::checkArgument(Objects::isArray($converters), "Parameter needs to be Array");
        $this->converters = $converters;
    }

    private function convert($obj, $formParamKey, $value) {
        if (Collections::hasKey($this->converters, $formParamKey)) {
            /** @var $converter DataConverter */
            $converter = Collections::getValue($this->converters, $formParamKey);
            return $converter->convert($obj, $value);
        }
        return $value;
    }

    /**
     * @return array
     */
    private function filterKeys($params) {
        $postParams = array();
        foreach ($params as $key => $v) {
            $fileteredKey = StringUtils::replace($key, self::SEPARATOR, ".");
            $postParams[$fileteredKey] = $v;
        }
        return $postParams;
    }

    /**
     * @param $postParams
     */
    private function checkMissingParams($postParams) {
        if (Objects::isNotNull($this->validators)) {
            $errors = $this->validators->checkFieldExist($postParams);
            $this->errorsHolder->addAllError($errors);
        }
    }

    public function hasErrors(): bool {
        return $this->errorsHolder->hasErrors();
    }
}