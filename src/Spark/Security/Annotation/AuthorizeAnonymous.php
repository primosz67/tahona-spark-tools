<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.07.14
 * Time: 00:17
 */

namespace Spark\Security\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;


/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class AuthorizeAnonymous implements Mapping\Annotation {

}