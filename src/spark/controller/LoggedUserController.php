<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.11.15
 * Time: 02:20
 */

namespace spark\controller;


use spark\Controller;
use spark\security\core\service\AuthenticationService;

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