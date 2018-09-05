<?php

namespace Spark\Persistence\tools;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\Driver\IBMDB2\DB2Connection;
use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\SchemaTool;
use Spark\Common\Collection\FluentIterables;
use Spark\Common\Exception\NotImplementedException;
use Spark\Config;
use Spark\Core\Annotation\Inject;
use Spark\Persistence\Annotation\Handler\EnableDataRepositoryAnnotationHandler;
use Spark\Persistence\tools\DataSource;
use Spark\Persistence\tools\DoctrineGenerateResult;
use Spark\Persistence\tools\DoctrineGeneratorConfiguration;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\FileUtils;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class DoctrineGenerator {


    /**
     * @Inject
     * @var Config
     */
    private $config;

    /**
     * @var EntityManagerFactory
     */
    private $entityManagerFactor;

    /**
     * DoctrineGenerator constructor.
     * @param EntityManagerFactory $entityManagerFactor
     */
    public function __construct(EntityManagerFactory $entityManagerFactor) {
        $this->entityManagerFactor = $entityManagerFactor;
    }

    /**
     * Generate entities by database.
     * @param $param
     */
    public function generate($param) {
        $em = $this->entityManagerFactor->createEntityManager($param);

// custom datatypes (not mapped for reverse engineering)
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

// fetch metadata
        $driver = new DatabaseDriver(
            $em->getConnection()->getSchemaManager()
        );
        $em->getConfiguration()->setMetadataDriverImpl($driver);
        $cmf = new DisconnectedClassMetadataFactory($em);
        $cmf->setEntityManager($em);
//        $classes = $driver->getAllClassNames();
        $metadata = $cmf->getAllMetadata();

        $nameSpace = $param['entityPackages'][0];
        foreach ($metadata as $data) {
            $data->namespace = $nameSpace;
            $data->name = $param['entityPackages'][0] . "\\" . $data->name;
        }

        $generator = new EntityGenerator();
        $generator->setUpdateEntityIfExists(true);
        $generator->setGenerateStubMethods(true);
        $generator->setGenerateAnnotations(true);
        $generator->generate($metadata, \Spark\Engine::getRootPath() . '/src/' . $nameSpace);

        print 'Done!';
    }

    /**
     *
     * @param DoctrineGeneratorConfiguration $configuration
     * @return DoctrineGenerateResult
     */
    public function updateSchema(DoctrineGeneratorConfiguration $configuration, $packages = array()) {
        $dbConfig = $configuration->getDbConfig();
        $em = $this->entityManagerFactor->createEntityManager($dbConfig);
        $genResult = new DoctrineGenerateResult();

        foreach ($packages as $namespace) {
            $entitiesFilePath = StringUtils::replace('src/' . $namespace, "\\", '/');
            $appParamPaths = $this->getAppParamPaths();

            foreach ($appParamPaths as $appParamPath) {
                $path = $appParamPath . '/' . $entitiesFilePath;

                if (FileUtils::isDir($path)) {
                    $fileList = FileUtils::getFileList($path);

                    $classes = array();

                    $schemaTool = new SchemaTool($em);

                    $className = '';

                    foreach ($fileList as $filePath) {
                        try {
                            $filePath = StringUtils::replace($filePath, '.php', '');
                            $namespace = StringUtils::replace($namespace, '/', "\\");

                            $className = $namespace . "\\" . $filePath;

                            $classes[] = $em->getClassMetadata($className);

                            $schemaTool->updateSchema($classes, true);

                            $genResult->addMessage('', 'Updated', $className);

                        } catch (\Exception $e) {
                            $genResult->addMessage($e->getMessage(), 'Error', $className);
                        }
                    }
                }

            }

        }

        return $genResult;
    }

    private function checkPathAppParam(array $paths): void {
        FluentIterables::of($paths)
            ->each(function ($singlePath) {
                $this->checkPath($singlePath);
            });

    }

    private function checkPath($path): void {
        Asserts::checkArgument(FileUtils::isDir($path), "DB Generator: Path not exist $path. Check Config ");
    }

    private function getAppParamPaths(): array {
        $appParamPaths = (array)$this->config->getProperty('app.paths');
        $this->checkPathAppParam($appParamPaths);
        return $appParamPaths;
    }


}
