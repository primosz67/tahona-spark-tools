<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.01.15
 * Time: 20:50
 */

namespace Spark\Persistence\tools;


class DataSource {

    //To set
    private $username;
    private $password;
    private $dbname;

    //TODO bad place
    private $proxyPath;
    private $entityPackages = array();

    private $driver = "pdo_mysql";
    private $host = "localhost";
    private $port = 3306;
    private $charset = "utf8";

    /**
     * @param string $proxyPath
     */
    public function setProxyPath($proxyPath) {
        $this->proxyPath = $proxyPath;
    }

    /**
     * @return string
     */
    public function getProxyPath() {
        return $this->proxyPath;
    }


    /**
     * @param string $charset
     */
    public function setCharset($charset) {
        $this->charset = $charset;
    }

    /**
     * @return string
     */
    public function getCharset() {
        return $this->charset;
    }

    /**
     * @param mixed $dbname
     */
    public function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    /**
     * @return mixed
     */
    public function getDbname() {
        return $this->dbname;
    }

    /**
     * @param string $driver
     */
    public function setDriver($driver) {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param int $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    public function setEntityPackages($entityPackages = array()) {
        $this->entityPackages = $entityPackages;
    }

    /**
     * @return array
     */
    public function getEntityPackages() {
        return $this->entityPackages;
    }


}