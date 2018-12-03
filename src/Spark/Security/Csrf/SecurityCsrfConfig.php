<?php


namespace Spark\Security\Csrf;


use Spark\Core\Annotation\Bean;
use Spark\Security\Csrf\View\CsrfSecurityViewSmartyPlugin;

class SecurityCsrfConfig {
    public const KEY = 'csrf';

    
    /**
     * @Bean()
     */
    public function crsfSecuritySmartyPlugin() {
        return new CsrfSecurityViewSmartyPlugin(self::KEY);
    }

    /**
     * @Bean()
     */
    public function crsfSecurityFilter() {
        return new CsrfSecurityFilter(self::KEY);
    }

}