<?php
/**
 *
 *
 * Date: 22.08.15
 * Time: 02:39
 */

namespace Spark\Persistence\fluent;


use Doctrine\ORM\EntityManager;
use Spark\Common\IllegalStateException;
use Spark\Common\Optional;
use Spark\Persistence\exception\EntityNotFoundException;
use Spark\Utils\Objects;

class EntityManagerFluentDataImpl implements FluentData {
    const NAME = 'fluentData';
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    /**
     * EntityName or class
     * @param $entityName
     * @return array
     */
    public function getAll($entityName) : array {
        return $this->em->getRepository($entityName)->findAll();
    }

    public function save($entity) {
        $this->em->transactional(function ($em) use ($entity) {
            /** @var EntityManager $em */
            $em->persist($entity);

        });
    }

    /**
     * @deprecated
     */
    public function findById(string $entityName, $id) {
        return $this->em->find($entityName, $id);
    }

    public function findByExample(string $entityName, array $example, $orderBy = array()): array {
        return $this->em->getRepository($entityName)
            ->findBy($example, $orderBy);
    }


    public function findOneByExample(string $entityName, array $example): Optional {
        return Optional::ofNullable($this->getOneByExample($entityName, $example));
    }

    /**
     * @throws \Exception
     */
    public function getOneByExample(string $entityName, $example) {
        $resultSet = $this->em->getRepository($entityName)
            ->findBy($example);

        if (count($resultSet) > 1) {
            throw new IllegalStateException('Retrieved more than one element! criteria: ' . get_class($this));
        } else if (isset($resultSet[0])) {
            return $resultSet[0];
        } else {
            return null;
        }
    }

    public function remove($entity) {
        $this->em->transactional(function ($em) use ($entity) {
            $em->remove($entity);
        });
    }

    /**
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function find(string $class, $id): Optional {
        $entity = $this->em->find($class, $id);
        return Optional::ofNullable($entity);
    }

    /**
     *
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get(string $class, $id) : object {
        $entity = $this->em->find($class, $id);
        if (Objects::isNull($entity)) {
            throw  new EntityNotFoundException('Entity not found');
        }
        return $entity;
    }

    public function detach($obj) {
        $this->em->detach($obj);
    }

    public function initialize(&$obj) {
        $this->em->initializeObject($obj);
    }
}