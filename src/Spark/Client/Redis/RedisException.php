<?php
/**
 * Date: 21.07.18
 * Time: 14:55
 */

namespace Spark\Client\Redis;


use Spark\Client\Exception\ClientException;

class RedisException extends ClientException {

    public static function wrap(\Exception $e) {
        return new self('Redis error', $e);
    }

}