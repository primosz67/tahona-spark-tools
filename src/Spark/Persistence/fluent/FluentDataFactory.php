<?php
/**
 * Date: 28.08.18
 * Time: 21:41
 */

namespace Spark\Persistence\fluent;


use Doctrine\ORM\EntityManager;

class FluentDataFactory {

    public static function ofEntityManager(EntityManager $em): FluentData {
        return new EntityManagerFluentDataImpl($em);
    }

}