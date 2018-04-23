<?php

namespace Spark\Form\Validator\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * Minimal value
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class MediaFile implements Mapping\Annotation {

    public $errorCode = "error.message.media.file";

    /**
     * @var array (start with comparison)  example: "image" or "image/png"
     */
    public $contentType = array();

    /**
     * @var int
     */
    public $maxSize = 2000; //kb

}