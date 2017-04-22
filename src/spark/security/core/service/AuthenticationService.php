<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.06.14
 * Time: 08:36
 */

namespace spark\security\core\service;


use spark\common\IllegalArgumentException;
use spark\http\utils\CookieUtils;
use spark\http\Session;
use spark\http\utils\RequestUtils;
use spark\core\service\ServiceHelper;
use spark\security\core\domain\AuthUser;
use spark\utils\Asserts;
use spark\utils\Collections;

class AuthenticationService extends ServiceHelper {

    const NAME = "authenticationService";

    const LOGGED_USER_SESSION_KEY = "spark_loggedUser";

    /**
     * @param $userName
     * @param array $roles
     * @param null $additionalDataObject
     * @throws IllegalArgumentException
     */
    public function authenticateUser($userName, $roles = array(), $additionalDataObject = null) {
        Asserts::notNull($userName);
        Asserts::checkArray($roles);

        /** @var $session Session */
        $authUser = new AuthUser($userName, $roles, $additionalDataObject);
        $session = RequestUtils::getOrCreateSession();
        $session->add(self::LOGGED_USER_SESSION_KEY, $authUser);
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
