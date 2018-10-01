<?php
/**
 * Date: 28.08.18
 * Time: 21:43
 */

namespace Spark\Persistence\fluent;


use Spark\Common\Optional;
use Spark\Persistence\exception\EntityNotFoundException;

interface FluentData {

    public function getAll(string $entityName): array;

    public function save($entity);

    public function findByExample(string $entityName, array $example, array $orderBy = array()): array;

    public function findOneByExample(string $entityName, array $example): Optional;

    public function remove($entity);

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function find(string $class, $id): Optional;

    /**
     * @return null|object
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get(string $class, $id);

    public function detach($obj);

    public function initialize(&$obj);
}