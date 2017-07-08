<?php
namespace spark\persistence;

use Doctrine\ORM\EntityManager;
use spark\Config;
use spark\core\annotation\Configuration;
use spark\core\annotation\PostConstruct;
use spark\core\annotation\Service;
use spark\core\annotation\Bean;
use spark\core\annotation\Inject;
use spark\core\provider\BeanProvider;
use spark\persistence\annotation\handler\EnableDataRepositoryAnnotationHandler;
use spark\persistence\annotation\RepositoryData;
use spark\persistence\tools\DataSource;
use spark\persistence\tools\DoctrineGenerator;
use spark\persistence\tools\EntityManagerFactory;
use spark\Container;


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