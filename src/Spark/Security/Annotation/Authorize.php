<?php
/**
 *
 * 
 * Date: 14.07.14
 * Time: 00:17
 */

namespace Spark\Security\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;


/**
 *
 * There are three  cases :
 * 1. $roles=null - no authentication
 * 2. $roles=array() - user authenticated
 * 3. $roles = ["ADMIN", "MEGA_USER"] - user with role "ADMIN" or "MEGA..." or both.
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
final class Authorize implements Mapping\Annotation {

    /**
     * @var array
     */
    public $roles;

}