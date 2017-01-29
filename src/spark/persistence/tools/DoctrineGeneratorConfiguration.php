<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.01.15
 * Time: 20:46
 */

namespace spark\persistence\tools;


class DoctrineGeneratorConfiguration {

    /**
     * @var  DbConfig
     */
    private $dbConfig;

    /**
     * Context project Path
     * @var string
     */
    private $filePath;

    /**
     * @var array
     */
    private $namespaces;

    public function setDbConfig(DbConfig $dbConfig) {
        $this->dbConfig = $dbConfig;
    }


    /**
     * @param string $filePath
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * @param array $namespace
     */
    public function setNamespaces($namespace = array()) {
        $this->namespaces = $namespace;
    }

    /**
     * @return array
     */
    public function getNamespaces() {
        return $this->namespaces;
    }

    /**
     * @return \spark\persistence\tools\DbConfig
     */
    public function getDbConfig() {
        return $this->dbConfig;
    }


}