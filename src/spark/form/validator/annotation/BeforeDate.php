<?php

namespace spark\form\validator\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class BeforeDate implements Mapping\Annotation {

    /** @var string */
    public $errorCode = "error.message.lenght";

    public $maxLength = null;
    public $minLength = null;
    public $length = null;

}