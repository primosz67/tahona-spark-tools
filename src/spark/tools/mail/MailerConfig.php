<?php
namespace spark\tools\mail;

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
use spark\tools\mail\Mailer;


/**
 * @Configuration()
 */
class MailerConfig {

    /**
     * @Inject()
     * @var Config
     */
    private $config;


    /**
     * @Bean()
     */
    public function mailer () {
        return new Mailer();
    }
}