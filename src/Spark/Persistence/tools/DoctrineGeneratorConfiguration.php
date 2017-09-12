<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.01.15
 * Time: 20:46
 */

namespace Spark\Persistence\tools;


class DoctrineGeneratorConfiguration {

    /**
     * @var  DataSource
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

    public function setDbConfig(DataSource $dbConfig) {
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
     * @return \Spark\Persistence\tools\DataSource
     */
    public function getDbConfig() {
        return $this->dbConfig;
    }


}