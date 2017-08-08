<?php


namespace spark\security\csrf;


use spark\core\annotation\Bean;
use spark\security\csrf\view\CsrfSecurityViewSmartyPlugin;

class SecurityCsrfConfig {
    const KEY = "csrf";

    
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