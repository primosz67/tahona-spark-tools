<?php

namespace spark\persistence\tools;

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
use spark\common\exception\NotImplementedException;
use spark\Config;
use spark\core\annotation\Inject;
use spark\persistence\annotation\handler\EnableDataRepositoryAnnotationHandler;
use spark\persistence\tools\DataSource;
use spark\persistence\tools\DoctrineGenerateResult;
use spark\persistence\tools\DoctrineGeneratorConfiguration;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\FileUtils;
use spark\utils\StringUtils;

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

        $nameSpace = $param["entityPackages"][0];
        foreach ($metadata as $data) {
            $data->namespace = $nameSpace;
            $data->name = $param["entityPackages"][0] . "\\" . $data->name;
        }

        $generator = new EntityGenerator();
        $generator->setUpdateEntityIfExists(true);
        $generator->setGenerateStubMethods(true);
        $generator->setGenerateAnnotations(true);
        $generator->generate($metadata, \spark\Engine::getRootPath() . "/src/" . $nameSpace);

        print 'Done!';
    }

    /**
     *
     * @param DoctrineGeneratorConfiguration $configuration
     * @return DoctrineGenerateResult
     */
    public function updateSchema(DoctrineGeneratorConfiguration $configuration, $packages=array()) {
        $dbConfig = $configuration->getDbConfig();
        $em = $this->entityManagerFactor->createEntityManager($dbConfig);
        $genResult = new DoctrineGenerateResult();

        foreach ($packages as $namespace) {
            $entitiesFilePath = StringUtils::replace("src/" . $namespace, "\\", "/");

            $path = $this->config->getProperty("app.path"). "/" . $entitiesFilePath;

            Asserts::checkArgument(FileUtils::isExist($path), "DB Generator: Path not exist $path. Check Config ");
            $fileList = FileUtils::getFileList($path);
            $classes = array();

            $schemaTool = new SchemaTool($em);

            $className = "";

            foreach ($fileList as $filePath) {
                try {
                    $filePath = StringUtils::replace($filePath, ".php", "");
                    $namespace = StringUtils::replace($namespace, "/", "\\");

                    $className = $namespace . "\\" . $filePath;

                    $classes[] = $em->getClassMetadata($className);

                    $schemaTool->updateSchema($classes, true);

                    $genResult->addMessage("", "Updated", $className);

                } catch (\Exception $e) {
                    $genResult->addMessage($e->getMessage(), "Error", $className);
                }
            }
        }

        return $genResult;
    }


}
