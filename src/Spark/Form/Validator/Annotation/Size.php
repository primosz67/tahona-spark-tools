<?php

namespace Spark\Form\Validator\Annotation;

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
    public $size = null;

}