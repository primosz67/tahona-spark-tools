<?php


namespace spark\form;

use spark\core\annotation\Bean;
use spark\core\annotation\Configuration;
use spark\form\filler\FormFiller;

/**
 * @Configuration()
 */
class FormConfig {


    /**
     * @Bean()
     */
    public function formFiller() {
        return new FormFiller();
    }

}