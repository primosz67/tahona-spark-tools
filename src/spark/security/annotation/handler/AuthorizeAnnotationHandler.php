<?php

namespace spark\security\annotation\handler;

use ReflectionMethod;
use spark\Config;
use spark\core\annotation\handler\AnnotationHandler;
use spark\core\annotation\handler\PathAnnotationHandler;
use spark\persistence\annotation\RepositoryData;
use spark\security\core\SecurityManager;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;
use spark\utils\ReflectionUtils;
use spark\utils\StringUtils;

/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 30.01.17
 * Time: 08:57
 */
class AuthorizeAnnotationHandler extends AnnotationHandler {

    private $annotationName;

    const DATA_REPOSITORY_ENABLED = "spark.data.repository.enabled";
    const DATA_REPOSITORY_PACKAGES = "spark.data.repository.package.list";
    const DATA_REPOSITORY = "spark.data.repository";

    public function __construct() {
        $this->annotationName = "spark\\security\\annotation\\Authorize";
    }

    public function handleMethodAnnotations($annotations = array(), $class, ReflectionMethod $methodReflection) {

        /** @var SecurityManager $securityManager */
        $securityManager = $this->getContainer()->get(SecurityManager::NAME);

        $roles = Collections::builder($annotations)
            ->filter(Predicates::compute($this->getClassName(), StringUtils::predEquals($this->annotationName)))
            ->flatMap(Functions::field("roles"))
            ->get();


        $paths = Collections::builder($annotations)
            ->filter(Predicates::compute($this->getClassName(), StringUtils::predEquals(PathAnnotationHandler::PATH_ANNOTATION)))
            ->flatMap(Functions::field("path"))
            ->get();

        if (Collections::isNotEmpty($roles)) {
            Asserts::checkState(Collections::isNotEmpty($paths), "@Authorization can be added only with @Path annotation. Class:".$class);
            $securityManager->addRoles($roles, $paths);
        }
    }


    private function getClassName() {
        return Functions::getClassName();
    }

}