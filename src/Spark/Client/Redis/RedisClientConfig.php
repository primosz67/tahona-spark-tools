<?php
/**
 * Date: 21.07.18
 * Time: 14:37
 */

namespace Spark\Client\Redis;


class RedisClientConfig {

    private $host;
    private $port;
    private $timeout;
    private $interval;
    private $objectTimeOut;

    /**
     * @param $timeout - connection timeout 5 seconds
     * @param $objectTimeOut - 3600 seconds
     * @param $interval - 160 milliseconds
     */
    public function __construct($host, $port = 6379, $timeout = 5, $interval = 150, $objectTimeOut = 3600) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->interval = $interval;
        $this->objectTimeOut = $objectTimeOut;
    }

    public function getHost() {
        return $this->host;
    }

    public function getPort(): int {
        return $this->port;
    }

    public function getTimeout(): int {
        return $this->timeout;
    }

    public function getInterval(): int {
        return $this->interval;
    }

    public function getObjectTimeOut(): int {
        return $this->objectTimeOut;
    }


}