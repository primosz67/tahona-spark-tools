<?php

namespace spark\persistence\annotation\handler;

use spark\Config;
use spark\core\annotation\handler\AnnotationHandler;
use spark\persistence\annotation\EnableDataRepository;
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
        $this->annotationName = "spark\\persistence\\annotation\\EnableDataRepository";
    }

    public function handleClassAnnotations($annotations = array(), $bean, \ReflectionClass $classReflection) {

        $annotation = Collections::builder($annotations)
            ->findFirst(Predicates::compute($this->getClassName(), StringUtils::predEquals($this->annotationName)));

        /** @var EnableDataRepository $ann */

        if ($annotation->isPresent()) {
            $ann = $annotation->get();
            $this->getConfig()->set(self::DATA_REPOSITORY_ENABLED, true);

            $this->getConfig()->add(self::DATA_REPOSITORY_PACKAGES, $ann->packages);

            $this->getConfig()->add(self::DATA_REPOSITORY, array(
                $ann->dataSourceName => array(
                    "dataSourceName" => $ann->dataSourceName,
                    "managerName" => $ann->managerName,
                    "packages" => $ann->packages
                )));
        }
    }

    private function getClassName() {
        return Functions::getClassName();
    }

}