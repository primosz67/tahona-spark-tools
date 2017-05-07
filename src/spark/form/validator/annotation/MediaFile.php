<?php

namespace spark\form\validator\annotation;

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
     * @var array (start with comparison)
     */
    public $contentType = array();

    /**
     * @var int
     */
    public $maxSize = 2000; //kb

}