<?php

namespace Spark\Form\Validator\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;

/**
 * Minimal value
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class IsTrue implements Mapping\Annotation {
    public $errorCode = 'error.message.true';
}