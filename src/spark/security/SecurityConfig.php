<?php
namespace spark\security;

use spark\Config;
use spark\core\annotation\Bean;
use spark\core\annotation\Configuration;
use spark\core\annotation\Inject;
use spark\security\annotation\handler\AuthorizeAnnotationHandler;
use spark\security\core\filter\SecurityFilter;
use spark\security\core\SecurityManager;
use spark\security\core\service\AuthenticationService;


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
     * @Bean
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