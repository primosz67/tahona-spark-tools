<?php

namespace spark\security\annotation\handler;

use ReflectionMethod;
use spark\Config;
use spark\core\annotation\handler\AnnotationHandler;
use spark\persistence\annotation\RepositoryData;
use spark\security\core\filter\SecurityFilter;
use spark\security\core\SecurityManager;
use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;
use spark\utils\StringUtils;

/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 30.01.17
 * Time: 08:57
 */
class EnableSecurityAnnotationHandler extends AnnotationHandler {

    private $annotationName;

    public function __construct() {
        $this->annotationName = "spark\\security\\annotation\\EnableSecurity";
    }

    public function handleClassAnnotations($annotations = array(), $class, \ReflectionClass $classReflection) {
        $repositoryAnnotations = Collections::builder($annotations)
            ->findFirst(Predicates::compute($this->getClassName(), StringUtils::predEquals($this->annotationName)));

        if ($repositoryAnnotations->isPresent()) {
            $this->getContainer()->registerObj(new SecurityFilter());
        }
    }

    private function getClassName() {
        return Functions::getClassName();
    }

}