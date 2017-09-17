<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.11.16
 * Time: 11:50
 */

namespace Spark\Form\Validator;


use Doctrine\Common\Annotations\AnnotationReader;
use Spark\Common\IllegalArgumentException;
use Spark\Core\Lang\LangMessageResource;
use Spark\Form\Validator\Annotation\MediaFile;
use Spark\Form\Validator\Annotation\Size;
use Spark\Form\Validator\Annotation\validator\AnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\DateAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\EmailAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\LengthAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\MaxAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\MediaFileAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\MinAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\NotBlankAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\NotNullAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\NumberAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\SizeAnnotationTypeValidator;
use Spark\Form\Validator\Annotation\validator\ZipCodeAnnotationTypeValidator;
use Spark\Utils\Asserts;
use Spark\Utils\Predicates;
use Spark\Utils\ReflectionUtils;
use Spark\Utils\ValidatorUtils;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class AnnotationValidator extends EntityValidator {

    private $annotationTypeValidators;

    /**
     * @var LangMessageResource
     */
    private $langMessageResource;

    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    private $annotationReader;

    /**
     * AnnotationValidator constructor.
     * @param LangMessageResource $langMessageResource
     * @param AnnotationReader $annotationReader
     */
    public function __construct(LangMessageResource $langMessageResource, AnnotationReader $annotationReader) {
        $this->langMessageResource = $langMessageResource;
        $this->annotationTypeValidators = array();
        $this->annotationReader = $annotationReader;
    }

    public function validateFieldValue($validatorKey, $obj, $field, $value) {
        $classNames = Objects::getClassNames($obj);

        return Collections::builder($classNames)
            ->map(function ($className) {
                return new \ReflectionClass($className);
            })
            ->filter(function ($reflectionClass) use ($field) {
                return $reflectionClass->hasProperty($field);
            })
            ->map(function ($reflectionClass) use ($field) {
                return $reflectionClass->getProperty($field);
            })
            ->flatMap(function ($reflectionProperty) use ($obj, $field, $value) {
                return Collections::builder($this->annotationTypeValidators)
                    ->map($this->getValidateFunction($obj, $value, $reflectionProperty))
                    ->filter(Predicates::notNull())
                    ->get();
            })
            ->get();


    }

    /**
     * @param $annotation
     * @return mixed
     */
    private function resolveMessage($annotation, $params = array()) {
        $str = $this->langMessageResource->get($annotation->errorCode, $params);
        if (StringUtils::isNotBlank($str)) {
            return $str;
        }
        return $annotation->errorCode;
    }

    /**
     * @param $obj
     * @return string
     */
    protected function getClassName($obj) {
        return Objects::getClassName($obj);
    }

    /**
     * @param $annotationTypeValidator AnnotationTypeValidator
     */
    public function addAnnotationTypeValidator($annotationTypeValidator) {
        if (!$annotationTypeValidator instanceof AnnotationTypeValidator) {
            throw  new IllegalArgumentException("AnnotationTypeValidator must be instance of type AnnotationTypeValidator");
        }

        $this->annotationTypeValidators[] = $annotationTypeValidator;
    }

    public function addDefaultValidators() {
        $this->addAnnotationTypeValidator(new NotBlankAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new NotNullAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new EmailAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new ZipCodeAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new DateAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new MinAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new MaxAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new NumberAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new SizeAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new LengthAnnotationTypeValidator());
        $this->addAnnotationTypeValidator(new MediaFileAnnotationTypeValidator());
    }

    /**
     *
     * @param $obj
     * @param $value
     * @param $reflectionProperty \ReflectionProperty
     * @return \Closure which return messages or null
     */
    private function getValidateFunction($obj, $value, $reflectionProperty) {
        return function ($typeValidator) use ($reflectionProperty, $obj, $value) {
            /** @var AnnotationTypeValidator $typeValidator */
            $annotation = $this->annotationReader->getPropertyAnnotation($reflectionProperty, $typeValidator->getAnnotationClassName());

            if (Objects::isNotNull($annotation) && !$typeValidator->isValid($obj, $value, $annotation)) {
                return $this->resolveMessage($annotation, $typeValidator->getAnnotationValues($annotation));
            }

            return null;
        };
    }

}