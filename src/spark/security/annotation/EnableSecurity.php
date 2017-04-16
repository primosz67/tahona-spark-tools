<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.07.14
 * Time: 00:17
 */

namespace spark\security\annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;


/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class EnableSecurity implements Mapping\Annotation {

    /**
     * @var boolean
     */
    public $enabled = true;

}