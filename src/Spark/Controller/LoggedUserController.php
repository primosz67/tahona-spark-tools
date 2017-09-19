<?php
/**
 *
 *
 * Date: 14.11.15
 * Time: 02:20
 */

namespace Spark\Controller;


use Spark\Controller;
use Spark\Security\Core\Service\AuthenticationService;

class LoggedUserController extends Controller {


    public function getLoggedUser() {
        return $this->getAuthenticationService()->getAuthUser();
    }

    /**
     * @return AuthenticationService
     */
    protected  function getAuthenticationService() {
        return $this->get(AuthenticationService::NAME);
    }

} 