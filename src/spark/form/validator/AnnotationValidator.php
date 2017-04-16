<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.11.16
 * Time: 11:50
 */

namespace spark\form\validator;


use spark\core\lang\LangMessageResource;
use spark\utils\ReflectionUtils;
use spark\utils\ValidatorUtils;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\StringUtils;

class AnnotationValidator extends EntityValidator {

    /**
     * @var LangMessageResource
     */
    //TODO - zrobić messageResolver
    private $langMessageResource;

    /**
     * AnnotationValidator constructor.
     * @param LangMessageResource $langMessageResource
     */
    public function __construct(LangMessageResource $langMessageResource) {
        $this->langMessageResource = $langMessageResource;
    }

    public function validateFieldValue($validatorKey, $obj, $field, $value) {
        $notBlank = $this->validateField($obj, $field, $value, "spark\\form\\validator\\annotation\\NotBlank", function ($value) {
            return StringUtils::isNotBlank($value);
        });

        $noNull = $this->validateField($obj, $field, $value, "spark\\form\\validator\\annotation\\NotNull", function ($value) {
            return Objects::isNotNull($value);
        });

        $email = $this->validateField($obj, $field, $value, "spark\\form\\validator\\annotation\\Email", function ($value) {
            return StringUtils::isBlank($value) || ValidatorUtils::isMailValid($value);
        });
        $zipCode = $this->validateField($obj, $field, $value, "spark\\form\\validator\\annotation\\ZipCode", function ($value) {
            return StringUtils::isBlank($value) || ValidatorUtils::isZipCodeValid($value);
        });

        $dateMessage = $this->validateField($obj, $field, $value, "spark\\form\\validator\\annotation\\Date", function ($value, $annotation) {
            return StringUtils::isBlank($value) || ValidatorUtils::isDate($value);
        });

        $annotationWithValueSupplier = $this->withValue();

//        if (Objects::isString($value) && StringUtils::contains($value, "user.password")) {
//            var_dump(StringUtils::isNotBlank($value));exit;
//        }

        $minMessage = $this->validateField($obj, $field, $value,
            "spark\\form\\validator\\annotation\\Min",
            function ($value, $annotation) {
                return StringUtils::isBlank($value) || $value >= $annotation->value;
            }, $annotationWithValueSupplier);

        $maxMessage = $this->validateField($obj, $field, $value,
            "spark\\form\\validator\\annotation\\Max",
            function ($value, $annotation) {
                return StringUtils::isBlank($value) || $value <= $annotation->value;
            }, $annotationWithValueSupplier);

        $size = $this->validateField($obj, $field, $value,
            "spark\\form\\validator\\annotation\\Size",
            function ($value, $annotation) {
                return Objects::isNull($value) || $value >= $annotation->min && $value <= $annotation->max || $value == $annotation->size;
            });

        $messages = Collections::builder()
            ->add($noNull)
            ->add($notBlank)
            ->add($email)
            ->add($zipCode)
            ->add($dateMessage)
            ->add($minMessage)
            ->add($maxMessage)
            ->add($size)
            ->filter(function ($x) {
                return Objects::isNotNull($x);
            })
            ->get();

        return $messages;
    }

    /**
     * @param $annotation
     * @return mixed
     */
    private function resolveMessage($annotation, \Closure $supplier) {
        if (Objects::isNotNull($this->langMessageResource)) {

            $str = $this->langMessageResource->get($annotation->errorCode, $supplier($annotation));
            if (StringUtils::isNotBlank($str)) {
                return $str;
            }
        }
        return $annotation->errorCode;
    }

    /**
     * @param $obj
     * @param $field
     * @param $value
     * @param $validationAnnotation
     * @param $apply
     *
     * @return string
     */
    private function validateField($obj, $field, $value, $validationAnnotation, $apply, $messageValuesSupplier = null) {
        $fullClassName = $this->getClassName($obj);
        $annotation = ReflectionUtils::getPropertyAnnotation($fullClassName, $field, $validationAnnotation);

        if (Objects::isNotNull($annotation)) {
            if (!$apply($value, $annotation)) {
                if (Objects::isNull($messageValuesSupplier)) {
                    $messageValuesSupplier = function ($x) {
                    };
                }
                return $this->resolveMessage($annotation, $messageValuesSupplier);
            }
        }
        return null;
    }

    /**
     * @param $obj
     * @return string
     */
    protected function getClassName($obj) {
        return Objects::getClassName($obj);
    }

    /**
     * @return \Closure
     */
    private function withValue() {
        return function ($annotation) {
            return array($annotation->value);
        };
    }

}