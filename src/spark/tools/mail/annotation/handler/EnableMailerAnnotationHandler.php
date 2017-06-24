<?php

namespace spark\tools\mail\annotation\handler;

use spark\core\annotation\handler\AnnotationHandler;
use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Predicates;
use spark\utils\StringUtils;

class EnableMailerAnnotationHandler extends AnnotationHandler {

    const SPARK_MAILER_ENABLED = "spark.mailer.enabled";

    private $annotationName;

    public function __construct() {
        $this->annotationName = "spark\\tools\\mail\\annotation\\EnableMailer";
    }

    public function handleClassAnnotations($annotations = array(), $class, \ReflectionClass $classReflection) {

        $annotation = Collections::builder($annotations)
            ->findFirst(Predicates::compute($this->getClassName(), StringUtils::predEquals($this->annotationName)));

        if ($annotation->isPresent()) {
            $this->getConfig()->set(self::SPARK_MAILER_ENABLED, true);
        }
    }

    private function getClassName() {
        return Functions::getClassName();
    }
}