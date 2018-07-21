<?php
/**
 * Date: 21.07.18
 * Time: 14:28
 */

namespace Spark\Cloud\Session\Redis;


use Spark\Client\Redis\RedisClient;
use Spark\Http\Cookie;
use Spark\Http\CookieImpl;
use Spark\Http\Session;
use Spark\Http\Session\SessionProvider;
use Spark\Security\Utils\PassUtils;
use Spark\Utils\Objects;

class RedisSessionProvider implements SessionProvider {

    /**
     * @var RedisClient
     */
    private $redisClient;
    /**
     * @var Cookie
     */
    private $cookie;

    public function __construct(RedisClient $redisClient) {
        $this->cookie = new CookieImpl();
        $this->redisClient = $redisClient;
    }

    public function getOrCreateSession(): Session {
        $prefix = $this->cookie->get('SESSID');
        if (Objects::isNull($prefix)) {
            $prefix = PassUtils::genCode(64);
            $this->cookie->set('SESSID', $prefix, 1800);
        }

        return new RedisSession($prefix, $this->redisClient);
    }

    public function getSession(): Session {
        return $this->getOrCreateSession();
    }

    public function hasSession(): bool {
        return Objects::isNotNull($this->cookie->get('SESSID'));
    }
}