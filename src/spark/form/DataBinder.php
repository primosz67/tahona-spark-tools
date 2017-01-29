<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.06.14
 * Time: 00:38
 */

namespace spark\form;


use spark\common\IllegalArgumentException;
use spark\form\converter\DataConverter;
use spark\form\validator\EntityValidator;
use spark\http\Request;
use spark\http\utils\RequestUtils;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\StringUtils;

class DataBinder {

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
    function __construct($params) {
        Asserts::notNull($params);
        $this->postParams = $params;
        $this->errorsHolder = new ErrorsHolder();

//        Asserts::checkState(Collections::isNotEmpty($params), "Post data cannot be empty");
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
                    $objectToEdit = & $this->objectReferences[$accessObjectKey];
                } else {

                    $objectToEdit = & $object;

                    for ($i = 0; $i < $index; $i++) {
                        $propertyName = $accessor[$i];
                        if (Objects::hasMethod($objectToEdit, "get".ucfirst($propertyName))){
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
                $objectToEdit = & $object;
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
        $methodName = "set".ucfirst($fields);

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

    public function isValid() {
        $errors = $this->getErrors();
        return empty($errors);
    }

    public function setValidators($validators) {
        if (is_array($validators)) {
            $this->validators = new EntityValidator($validators);
        } else if ($validators instanceOf EntityValidator){
            $this->validators = $validators;
        }
    }


    public function setDataConverters($converters = array()) {
        Asserts::checkArgument(Objects::isArray($converters), "Parameter needs to be Array");

        foreach($converters as $key => $converter) {
            Asserts::checkArgument(!is_numeric($key), "Key for converter cannot be number");

            $converters[StringUtils::replace($key, ".", self::SEPARATOR)] = $converter;
            Collections::removeByIndex($converters, $key);
        }

        $this->converters = $converters;
    }

    private function convert($obj, $fields, $value) {
        if (Collections::hasKey($this->converters, $fields)) {
            /** @var $converter DataConverter */
            $converter = Collections::getValue($this->converters, $fields);
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
            $filetereKey = StringUtils::replace($key, self::SEPARATOR, ".");
            $postParams[$filetereKey] = $v;
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


}