<?php
namespace Spark\Security;

use Spark\Config;
use Spark\Core\Annotation\Bean;
use Spark\Core\Annotation\Configuration;
use Spark\Core\Annotation\Inject;
use Spark\Http\Session\SessionProvider;
use Spark\Security\Annotation\Handler\AuthorizeAnnotationHandler;
use Spark\Security\Core\Filter\SecurityFilter;
use Spark\Security\Core\SecurityManager;
use Spark\Security\Core\Service\AuthenticationService;


class SecurityConfig {

    /**
     * @Inject()
     * @var SessionProvider
     */
    private $sessionProvider;

    /**
     * @Bean()
     */
    public function securityManager() {
        return new SecurityManager();
    }

    /**
     * @Bean()
     */
    public function authenticationService() {
        return new AuthenticationService($this->sessionProvider);
    }

    /**
     * @Bean()
     */
    public function securityFilter() {
        return new SecurityFilter();
    }

    /**
     * @Bean()
     */
    public function authorizeAnnotationHandler() {
        return new AuthorizeAnnotationHandler();
    }

}