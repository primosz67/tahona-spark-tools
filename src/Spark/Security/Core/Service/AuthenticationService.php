<?php
/**
 *
 *
 * Date: 25.06.14
 * Time: 08:36
 */

namespace Spark\Security\Core\Service;

use Spark\Common\IllegalArgumentException;
use Spark\Http\Session;
use Spark\Http\Session\SessionProvider;
use Spark\Http\Utils\CookieUtils;
use Spark\Security\Core\Domain\AuthUser;
use Spark\Utils\Asserts;
use Spark\Utils\Objects;

class AuthenticationService {

    public const NAME = 'authenticationService';
    public const LOGGED_USER_SESSION_KEY = 'spark_loggedUser';

    private $sessionProvider;

    public function __construct(SessionProvider $sessionProvider) {
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * @param string $userName
     * @param array $roles
     * @param null $additionalDataObject
     * @throws IllegalArgumentException
     * @return AuthUser
     */
    public function authenticateUser($userName, $roles = array(), $additionalDataObject = null) {
        Asserts::notNull($userName);
        Asserts::checkState(Objects::isString($userName), 'Username must be string but something else given.');
        Asserts::checkArray($roles);

        /** @var $session Session */
        $authUser = new AuthUser($userName, $roles, $additionalDataObject);
        $session = $this->sessionProvider->getOrCreateSession();
        $session->add(self::LOGGED_USER_SESSION_KEY, $authUser);
        return $authUser;
    }

    public function isLogged() {
        /** @var $session Session */
        $session = $this->sessionProvider->getSession();
        return $session->has(self::LOGGED_USER_SESSION_KEY);
    }

    public function removeUser() {
        /** @var $session Session */
        $session = $this->sessionProvider->getSession();
        $session->remove(self::LOGGED_USER_SESSION_KEY);
        CookieUtils::removeCookie(session_name());
    }

    /**
     * @return AuthUser
     * @throws IllegalArgumentException
     */
    public function getAuthUser(): AuthUser {
        if ($this->isLogged()) {
            $session = $this->sessionProvider->getOrCreateSession();
            return $session->get(self::LOGGED_USER_SESSION_KEY);
        }
        $this->removeUser();
        throw new IllegalArgumentException('No auth user in session');
    }
}
