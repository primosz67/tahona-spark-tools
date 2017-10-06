<?php


namespace Spark\Tools\Mail;


class MailerConfig {


    private $hosts = [];
    private $userName;
    private $password;
    private $port = 587;
    private $securityProtocol = "tls";

    /**
     * @return array
     */
    public function getHosts() {
        return $this->hosts;
    }

    public function setHosts(array $hosts) {
        $this->hosts = $hosts;
    }

    /**
     * @return mixed
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName) {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getPort(): int {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port) {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getSecurityProtocol(): string {
        return $this->securityProtocol;
    }

    /**
     * @param string $securityProtocol
     */
    public function setSecurityProtocol(string $securityProtocol) {
        $this->securityProtocol = $securityProtocol;
    }


}