<?php

namespace spark\form\validator\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * Minimal value
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class Min implements Mapping\Annotation {

    public $errorCode = "error.message.min";

    /**
     * @var int
     */
    public $value = 0;

}