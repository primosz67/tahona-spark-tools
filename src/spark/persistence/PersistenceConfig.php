<?php
namespace spark\persistence;

use Doctrine\ORM\EntityManager;
use spark\Config;
use spark\core\annotation\Configuration;
use spark\core\annotation\PostConstruct;
use spark\core\annotation\Service;
use spark\core\di\Bean;
use spark\core\di\Inject;
use spark\core\provider\BeanProvider;
use spark\persistence\annotation\handler\EnableDataRepositoryAnnotationHandler;
use spark\persistence\tools\DoctrineGenerator;
use spark\persistence\tools\EntityManagerFactory;
use spark\Services;


/**
 * @Configuration()
 */
class PersistenceConfig {

    /**
     * @Inject()
     * @var
     */
    private $dataSource;

    /**
     * @Inject()
     * @var Config
     */
    private $config;

    /**
     * @Inject()
     * @var Services
     */
    private $services;

    /**
     * @PostConstruct()
     * @return null
     * @throws \Exception
     */
    public function entityManager() {

        $property = $this->config->getProperty(EnableDataRepositoryAnnotationHandler::DATA_REPOSITORY, array());

        foreach($property as  $k => $v) {
            $entityManagerFactory = new EntityManagerFactory($this->config);
            $bean = $this->services->get($v["dataSourceName"]);
            $entityManager = $entityManagerFactory->createEntityManager($bean);

            $this->services->register($v["managerName"], $entityManager);
        }

    }

    /**
     * @Bean()
     */
    public function doctrineGenerator() {
        return new DoctrineGenerator(new EntityManagerFactory($this->config));
    }


}