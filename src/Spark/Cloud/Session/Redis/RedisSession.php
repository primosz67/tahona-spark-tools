<?php
/**
 * Date: 21.07.18
 * Time: 15:28
 */

namespace Spark\Cloud\Session\Redis;


use Spark\Client\Redis\RedisClient;
use Spark\Common\Exception\UnsupportedOperationException;
use Spark\Http\Session;

class RedisSession implements Session {

    private $redisClient;
    /**
     * @var string
     */
    private $prefix;

    public function __construct(string $prefix, RedisClient $redisClient) {
        $this->redisClient = $redisClient;
        $this->prefix = $prefix;
    }

    public function add($key, $value): Session {
        $this->redisClient->put($this->getKey($key), $value);
        return $this;
    }

    public function addAll(array $array): Session {
        foreach ($array as $key => $value) {
            $this->redisClient->put($this->getKey($key), $value);
        }
        return $this;
    }

    /**
     * @return array
     * @throws UnsupportedOperationException
     */
    public function getParams(): array {
        throw new UnsupportedOperationException('Redis session is not supported');
    }

    public function has($key): bool {
        return $this->redisClient->has($this->getKey($key));
    }

    public function get($key) {
        return $this->redisClient->get($this->getKey($key));
    }

    public function remove($key): Session {
        $this->redisClient->remove($this->getKey($key));
        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    private function getKey($key): string {
        return $this->prefix .'_'. $key;
    }
}