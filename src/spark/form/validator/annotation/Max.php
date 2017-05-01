<?php

namespace spark\form\validator\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * Maximal value
 *
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class Max implements Mapping\Annotation {

    public $errorCode = "error.message.max";

    /**
     * @var int
     */
    public $value = null;

}