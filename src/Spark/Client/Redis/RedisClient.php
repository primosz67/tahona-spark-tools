<?php
/**
 * Date: 21.07.18
 * Time: 14:35
 */

namespace Spark\Client\Redis;

use Redis;
use Spark\Cache\Cache;
use Spark\Common\IllegalStateException;
use Spark\Utils\BooleanUtils;
use Spark\Utils\Collections;
use Spark\Utils\JsonUtils;
use Spark\Utils\Objects;

class RedisClient {

    /**
     * @var Redis
     */
    private $redis;
    /**
     * @var RedisClientConfig
     */
    private $config;

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(RedisClientConfig $config) {
        $this->redis = new Redis();
        $this->config = $config;
    }

    public function get(string $key) {
        if ($this->isCached($key)) {
            return $this->cache->get($key);
        }

        return $this->invoke(function () use ($key) {
            $string = $this->redis->get($key);
            if (BooleanUtils::isFalse($string)) {
                return null;
            }

            $data = unserialize($string);
            $this->cache->put($key, $data);
            return $data;
        });
    }

    public function put(string $key, $object, $time = null) {
        $this->invoke(function () use ($key, $object, $time) {
            $expireTime = Objects::defaultIfNull($time, $this->config->getObjectTimeOut());
            if (Objects::isNotNull($expireTime)) {
                $this->redis->setex($key, $expireTime, serialize($object));
            } else {
                $this->redis->set($key, serialize($object));
            }
        });
    }

    public function remove(string $key): void {
        $this->invoke(function () use ($key) {
            $this->redis->del($key);
        });
    }

    public function has(string $key): bool {
        if ($this->isCached($key)) {
            return true;
        }

        return (bool)$this->invoke(function () use ($key) {
            return $this->redis->exists($key);
        });
    }

    /**
     * @param $action
     * @throws \Spark\Client\Redis\RedisException
     */
    private function invoke($action) {
        try {
            $this->redis->connect(
                $this->config->getHost(),
                $this->config->getPort(),
                $this->config->getTimeout(),
                $this->config->getInterval());

            return $action();
        } catch (\Exception $e) {

            throw RedisException::wrap($e);
        }
    }

    public function setCache(Cache $cache) {
        $this->cache = $cache;
    }

    private function isConnected(): bool {
        try {
            $this->redis->ping();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    private function isCached($key) {
        return $this->cache !== null && $this->cache->has($key);
    }


}