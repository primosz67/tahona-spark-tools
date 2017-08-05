<?php

namespace spark\persistence\annotation\handler;

use spark\Config;
use spark\core\annotation\handler\AnnotationHandler;
use spark\persistence\annotation\RepositoryData;
use spark\persistence\tools\EntityManagerFactory;
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
class EnableDataRepositoryAnnotationHandler extends AnnotationHandler {

    private $annotationName;

    const DATA_REPOSITORY_ENABLED = "spark.data.repository.enabled";
    const DATA_REPOSITORY_PACKAGES = "spark.data.repository.package.list";
    const DATA_REPOSITORY = "spark.data.repository";

    public function __construct() {
        $this->annotationName = "spark\\persistence\\annotation\\RepositoryData";
    }

    public function handleClassAnnotations($annotations = array(), $class, \ReflectionClass $classReflection) {

        $repositoryAnnotation = Collections::builder($annotations)
            ->filter(Predicates::compute($this->getClassName(), Predicates::equals($this->annotationName)))
            ->get();

        foreach ($repositoryAnnotation as $repAnnotation) {

            /** @var RepositoryData $ann */
            $ann = $repAnnotation;
            $this->getConfig()->set(self::DATA_REPOSITORY_ENABLED, true);
            $this->getConfig()->add(self::DATA_REPOSITORY_PACKAGES, $ann->packages);

            $this->getConfig()->add(self::DATA_REPOSITORY, array(
                $ann->dataSource => array(
                    "dataSource" => $ann->dataSource,
                    "manager" => $ann->manager,
                    "packages" => $ann->packages
                )));
        }
    }

    private function getClassName() {
        return Functions::getClassName();
    }

}