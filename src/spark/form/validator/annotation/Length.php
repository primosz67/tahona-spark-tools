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
final class Length implements Mapping\Annotation {

    /** @var string */
    public $errorCode = "error.message.length";

    /**
     * @var int
     */
    public $min = null;

    /**
     * @var int
     */
    public $max = null;

    /**
     * @var int
     */
    public $length = null;

}