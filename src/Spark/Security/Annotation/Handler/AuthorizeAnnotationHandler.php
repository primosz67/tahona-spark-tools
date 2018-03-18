<?php

namespace Spark\Security\Annotation\Handler;

use ReflectionClass;
use ReflectionMethod;
use Spark\Config;
use Spark\Core\Annotation\Handler\AnnotationHandler;
use Spark\Core\Annotation\Handler\PathAnnotationHandler;
use Spark\Persistence\Annotation\RepositoryData;
use Spark\Security\Annotation\Authorize;
use Spark\Security\Core\SecurityManager;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\Functions;
use Spark\Utils\Objects;
use Spark\Utils\Predicates;
use Spark\Utils\ReflectionUtils;
use Spark\Utils\StringUtils;

/**
 *
 * 
 * Date: 30.01.17
 * Time: 08:57
 */
class AuthorizeAnnotationHandler extends AnnotationHandler {

    private $annotationName;

    private $securityManager;

    public function __construct() {
        $this->annotationName = Authorize::class;
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