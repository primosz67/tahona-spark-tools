<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 22.08.15
 * Time: 02:39
 */

namespace spark\persistence\fluent;


use Doctrine\ORM\EntityManager;
use spark\common\IllegalStateException;

class FluentData {
    const NAME = "fluentData";
    /**
     * @var EntityManager
     */
    private $em;

    function __construct($em) {
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

    public function findById($entityName, $id) {
        return $this->em->find($entityName, $id);
    }

    public function findByExample($getEntityName, $example, $orderBy = array()) {
        return $this->em->getRepository($getEntityName)
            ->findBy($example, $orderBy);
    }

    /**
     * @deprecated  use getOneByExample
     */
    public function findOneByExample($entityName, $example) {
        return $this->getOneByExample($entityName, $example);
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
        $this->em->remove($entity);
    }
}