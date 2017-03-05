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
     * @Inject()
     * @var Container
     */
    private $container;

    /**
     * @PostConstruct()
     * @return null
     * @throws \Exception
     */
    public function entityManager() {
        $property = $this->config->getProperty(EnableDataRepositoryAnnotationHandler::DATA_REPOSITORY, array());

        $entityManagerFactory = new EntityManagerFactory($this->config);

        foreach ($property as $k => $v) {

            $dataSource = $this->container->get($v["dataSource"]);
            $entityManager = $entityManagerFactory->createEntityManager($dataSource);

            $this->container->register($v["manager"], $entityManager);
        }
    }

    /**
     * @Bean()
     */
    public function doctrineGenerator() {
        return new DoctrineGenerator(new EntityManagerFactory($this->config));
    }


}