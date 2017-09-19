<?php

namespace Spark\Persistence\Annotation\Handler;

use Spark\Config;
use Spark\Core\Annotation\Handler\AnnotationHandler;
use Spark\Persistence\Annotation\RepositoryData;
use Spark\Persistence\tools\EntityManagerFactory;
use Spark\Utils\Collections;
use Spark\Utils\Functions;
use Spark\Utils\Objects;
use Spark\Utils\Predicates;
use Spark\Utils\StringUtils;

/**
 *
 *
 * Date: 30.01.17
 * Time: 08:57
 */
class EnableDataRepositoryAnnotationHandler extends AnnotationHandler {

    private $annotationName;

    const DATA_REPOSITORY_ENABLED = "Spark.data.repository.enabled";
    const DATA_REPOSITORY_PACKAGES = "Spark.data.repository.package.list";
    const DATA_REPOSITORY = "Spark.data.repository";

    public function __construct() {
        $this->annotationName = RepositoryData::class;
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