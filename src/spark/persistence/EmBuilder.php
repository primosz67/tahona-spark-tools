<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 29.01.17
 * Time: 12:06
 */

namespace spark\persistence;


use Doctrine\ORM\EntityManager;
use spark\Config;
use spark\persistence\tools\EntityManagerBuilder;

class EmBuilder {

    const ENTITY_MANAGER = "entityManager";

    private $em;
    private $config;

    /**
     * EmBuilder constructor.
     * @param $config
     */
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     *
     * @return EntityManager
     */
    public function buildEm() {
        if (empty($this->em)) {
            $this->em = $this->getCreateEM();
        }
        return $this->em;
    }

    //TODO - change this later

    private function getCreateEM() {

        $config = $this->getConfig();
        if ($config->hasProperty("db")) {

            $entityManager = EntityManagerBuilder::build(
                array(
                    "mode" => $config->getMode(),
                    "user" => $config->getProperty("db.user"),
                    "password" => $config->getProperty("db.password"),
                    "host" => $config->getProperty("db.host"),
                    "database" => $config->getProperty("db.database"),
                    //TODO
                    "entityPackages" => $config->getProperty("db.entityPackages"),
                    "proxyPath" => $config->getProperty("db.proxyPath"),
                    "dev" => $config->getProperty("dev")
//                    "entityPaths" => $config->getProperty("db.entitiesPath"),
//                    "proxyPath" => $config->getProperty("db.proxyPath"),
//                    "dev" => $config->getProperty("dev")
                )
            );
            return $entityManager;
        } else {
            return null;
        }

    }

    /**
     * @return Config
     */
    private function getConfig() {
        return $this->config;
    }

}