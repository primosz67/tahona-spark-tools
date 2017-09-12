<?php
namespace Spark\Security;

use Spark\Config;
use Spark\Core\Annotation\Bean;
use Spark\Core\Annotation\Configuration;
use Spark\Core\Annotation\Inject;
use Spark\Security\Annotation\Handler\AuthorizeAnnotationHandler;
use Spark\Security\Core\Filter\SecurityFilter;
use Spark\Security\Core\SecurityManager;
use Spark\Security\Core\Service\AuthenticationService;


/**
 * @Configuration()
 */
class SecurityConfig {

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
        return new AuthenticationService();
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