<?php
/**
 *
 *
 * Date: 14.11.15
 * Time: 02:20
 */

namespace Spark\Controller;


use Spark\Controller;
use Spark\Core\Annotation\Inject;
use Spark\Core\Annotation\PostConstruct;
use Spark\Core\Provider\BeanProvider;
use Spark\Security\Core\Service\AuthenticationService;

class LoggedUserController extends Controller {

    private $authService;

    /**
     * @Inject
     * @var BeanProvider
     */
    private $beanProvider;

    /**
     * @PostConstruct()
     */
    public function initBeans() {
        $this->authService = $this->beanProvider->getBean(AuthenticationService::NAME);
        $this->beanProvider = null;
    }

    public function getLoggedUser() {
        return $this->getAuthenticationService()->getAuthUser();
    }

    /**
     * @return AuthenticationService
     */
    protected function getAuthenticationService() {
        return $this->authService;
    }

} 