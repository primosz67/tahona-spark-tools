<?php

namespace spark\security\annotation\handler;

use ReflectionClass;
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

    private $securityManager;

    public function __construct() {
        $this->annotationName = "spark\\security\\annotation\\Authorize";
    }

    public function handleClassAnnotations($annotations = array(), $class, ReflectionClass $classReflection) {
        $authorizeAnnotations = $this->getAnnotations($annotations);

        if (Collections::isNotEmpty($authorizeAnnotations)) {
            $roles = $this->getRoles($authorizeAnnotations);

            if (Objects::isNotNull($roles)) {
                $this->getSecurity()->addClassRoles($class, $roles);
            }
        }
    }

    public function handleMethodAnnotations($annotations = array(), $class, ReflectionMethod $methodReflection) {

        $authorizeAnnotations = $this->getAnnotations($annotations);

        if (Collections::isNotEmpty($authorizeAnnotations)) {
            $roles = $this->getRoles($authorizeAnnotations);

            if (Objects::isNotNull($roles)) {
                $class = $methodReflection->getDeclaringClass();
                $this->getSecurity()->addMethodRoles($class->getName(), $methodReflection->getName(), $roles);
            }
        }
    }


    private function getClassName() {
        return Functions::getClassName();
    }

    /**
     *
     * @return SecurityManager
     * @throws \Exception
     */
    private function getSecurity() {
        /** @var SecurityManager $securityManager */
        if (Objects::isNull($this->securityManager)) {
            $this->securityManager = $this->getContainer()->get(SecurityManager::NAME);
        }
        return $this->securityManager;
    }

    /**
     *
     * @param $annotations
     * @return array
     */
    private function getAnnotations($annotations) {
        $authorizeAnnotations = Collections::builder($annotations)
            ->filter(Predicates::compute($this->getClassName(), Predicates::equals($this->annotationName)))
            ->get();
        return $authorizeAnnotations;
    }

    /**
     *
     * @param $authorizeAnnotations
     * @return array
     */
    private function getRoles($authorizeAnnotations) {
        $roles = Collections::builder($authorizeAnnotations)
            ->flatMap(Functions::field("roles"))
            ->filter(Predicates::notNull())
            ->get();
        return $roles;
    }

}