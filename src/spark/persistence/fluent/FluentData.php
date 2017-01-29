<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 22.08.15
 * Time: 02:39
 */

namespace spark\persistence\fluent;


use Doctrine\ORM\EntityManager;

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
        return $this->em->persist($entity);
    }

    public function findById($entityName, $id) {
        return $this->em->find($entityName, $id);
    }

    public function findByExample($getEntityName, $example, $orderBy = array()) {
        return $this->em->getRepository($getEntityName)
            ->findBy($example, $orderBy);
    }

    public function findOneByExample($getEntityName, $example) {
        $resultSet = $this->em->getRepository($getEntityName)
            ->findBy($example);

        if (count($resultSet) > 1) {
            throw new \Exception("Retrieved more than one element! criteria: ".get_class($this));
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