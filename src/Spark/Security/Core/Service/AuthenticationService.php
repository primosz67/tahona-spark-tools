<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.06.14
 * Time: 08:36
 */

namespace Spark\Security\Core\Service;


use Spark\Common\IllegalArgumentException;
use Spark\Http\Utils\CookieUtils;
use Spark\Http\Session;
use Spark\Http\Utils\RequestUtils;
use Spark\Core\Service\ServiceHelper;
use Spark\Security\Core\Domain\AuthUser;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\Objects;

class AuthenticationService {

    const NAME = "authenticationService";

    const LOGGED_USER_SESSION_KEY = "spark_loggedUser";

    /**
     * @param string $userName
     * @param array $roles
     * @param null $additionalDataObject
     * @throws IllegalArgumentException
     * @return AuthUser
     */
    public function authenticateUser($userName, $roles = array(), $additionalDataObject = null) {
        Asserts::notNull($userName);
        Asserts::checkState(Objects::isString($userName), "Username must be string but something else given.");
        Asserts::checkArray($roles);

        /** @var $session Session */
        $authUser = new AuthUser($userName, $roles, $additionalDataObject);
        $session = RequestUtils::getOrCreateSession();
        $session->add(self::LOGGED_USER_SESSION_KEY, $authUser);
        return $authUser;
    }

    public function isLogged() {
        /** @var $session Session */
        $session = RequestUtils::getSession();
        return $session->has(self::LOGGED_USER_SESSION_KEY);
    }

    public function removeUser() {
        /** @var $session Session */
        $session = RequestUtils::getSession();
        $session->remove(self::LOGGED_USER_SESSION_KEY);
        CookieUtils::removeCookie(RequestUtils::SESSION_NAME);
    }

    /**
     * @return AuthUser
     */
    public function getAuthUser() {
        if ($this->isLogged()) {
            $session = RequestUtils::getOrCreateSession();
            return $session->get(self::LOGGED_USER_SESSION_KEY);

        } else {
            throw new IllegalArgumentException("No auth user in session");
        }
    }

}
