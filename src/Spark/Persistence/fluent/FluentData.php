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

class FluentData {
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
    public function getAll($entityName) {
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
    public function findById($entityName, $id) {
        return $this->em->find($entityName, $id);
    }

    public function findByExample($getEntityName, $example, $orderBy = array()) {
        return $this->em->getRepository($getEntityName)
            ->findBy($example, $orderBy);
    }


    public function findOneByExample($entityName, $example) {
        return Optional::ofNullable($this->getOneByExample($entityName, $example));
    }

    /**
     * @param $entityName
     * @param $example
     * @return null
     * @throws \Exception
     */
    public function getOneByExample($entityName, $example) {
        $resultSet = $this->em->getRepository($entityName)
            ->findBy($example);

        if (count($resultSet) > 1) {
            throw new IllegalStateException("Retrieved more than one element! criteria: " . get_class($this));
        } else if (isset($resultSet[0])) {
            return $resultSet[0];
        } else {
            return null;
        }
    }

    public function remove($entity) {
        $this->em->transactional(function($em) use ($entity) {
            $em->remove($entity);
        });
    }

    /**
     *
     * @param $class
     * @param $getId
     * @return Optional
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function find($class, $getId) {
        $entity = $this->em->find($class, $getId);
        return Optional::ofNullable($entity);
    }

    /**
     *
     * @param $class
     * @param $id
     * @return null|object
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function get($class, $id) {
        $entity = $this->em->find($class, $id);
        if (Objects::isNull($entity)) {
            throw  new EntityNotFoundException();
        }
        return $entity;
    }

    public function detach($obj) {
        $this->em->detach($obj);
    }

    public function initialize(&$obj){
        $this->em->initializeObject($obj);
    }
}