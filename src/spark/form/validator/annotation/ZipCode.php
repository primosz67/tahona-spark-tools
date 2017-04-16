<?php

namespace spark\form\validator\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class ZipCode implements Mapping\Annotation {

    /** @var string */
    public $errorCode = "error.message.zipCode";

}