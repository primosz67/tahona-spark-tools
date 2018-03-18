<?php
namespace Spark\Persistence;

use Doctrine\ORM\EntityManager;
use Spark\Config;
use Spark\Core\Annotation\Configuration;
use Spark\Core\Annotation\PostConstruct;
use Spark\Core\Annotation\Service;
use Spark\Core\Annotation\Bean;
use Spark\Core\Annotation\Inject;
use Spark\Core\Provider\BeanProvider;
use Spark\Persistence\Annotation\Handler\EnableDataRepositoryAnnotationHandler;
use Spark\Persistence\Annotation\RepositoryData;
use Spark\Persistence\tools\DataSource;
use Spark\Persistence\tools\DoctrineGenerator;
use Spark\Persistence\tools\EntityManagerFactory;
use Spark\Container;


/**
 * @Configuration()
 */
class PersistenceConfig {

    /**
     * @Inject()
     * @var Config
     */
    private $config;

    /**
     * @Bean()
     */
    public function entityManagerFactory() {
        return new EntityManagerFactory($this->config);
    }

    /**
     * @Bean()
     */
    public function doctrineGenerator() {
        return new DoctrineGenerator(new EntityManagerFactory($this->config));
    }

    public function enableDataRepositoryAnnotationHandler() {
        return new EnableDataRepositoryAnnotationHandler();
    }
}