<?php

namespace spark\form\validator\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 *  size of String, Collection, Array
 *
 *
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class Size implements Mapping\Annotation {

    /** @var string */
    public $errorCode = "error.message.size";

    public $min = null;
    public $max = null;
    public $size = null;

}