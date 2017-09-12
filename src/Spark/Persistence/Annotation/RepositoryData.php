<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 14.07.14
 * Time: 00:17
 */

namespace Spark\Persistence\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\Mapping;


/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class RepositoryData implements Mapping\Annotation {

    /**
     * @var string
     */
    public $dataSource = "dataSource";
    /**
     * @var string
     */
    public $manager = "entityManager";

    /** @var array */
    public $packages = array();

} 