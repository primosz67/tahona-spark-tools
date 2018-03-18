<?php


namespace Spark\Form;

use Spark\Core\Annotation\Bean;
use Spark\Core\Annotation\Configuration;
use Spark\Form\Filler\FormFiller;

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