<?php

namespace spark\persistence\crud;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use spark\common\Optional;
use spark\core\annotation\Inject;
use spark\persistence\criteria\CriteriaHandler;
use spark\tools\pagination\PaginationParams;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;

/**
 * Description of CrudDao
 *
 * @author primosz67
 */
abstract class CrudDao {

    /**
     * @Inject()
     * @var EntityManager
     */
    private $entityManager;

    public function __construct() {

    }

    /**
     *
     * @return EntityManager
     */
    protected function getEm() {
        return $this->entityManager;
    }

    /**
     *
     * @return QueryBuilder
     */
    protected function getQBuilder() {
        return $this->getEm()->createQueryBuilder();
    }

    /**
     *
     * @param $id
     * @return null|object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findById($id) {
        return $this->getEm()->find($this->getEntityName(), $id);
    }

    public function save($entity) {
        $this->getEm()->transactional(function ($em) use ($entity) {
            /** @var EntityManager $em */
            $em->persist($entity);

        });
    }

    public function saveAll($entities) {
        $this->getEm()->transactional(function ($em) use ($entities) {
            /** @var EntityManager $em */
            foreach ($entities as $entity) {
                $em->persist($entity);
            }
        });

    }

    public function getAll() {
        return $this->getEm()->getRepository($this->getEntityName())
            ->findAll();
    }

    public abstract function getEntityName();

    /**
     * @param array $example
     * @param array $orderBy array(property=>ASC)
     * @return array
     */
    public function findByExample($example = array(), $orderBy = array()) {
        return $this->getEm()->getRepository($this->getEntityName())
            ->findBy($example, $orderBy);
    }


    /**
     * @param $example
     * @return Optional
     */
    public function findOneByExample($example) {
        return Optional::ofNullable($this->getOneByExample($example));
    }

    public function getOneByExample($example) {
        $resultSet = $this->getEm()->getRepository($this->getEntityName())
            ->findBy($example);

        if (count($resultSet) > 1) {
            throw new Exception("Retrieved more than one element! criteria: " . get_class($this));
        } else if (isset($resultSet[0])) {
            return $resultSet[0];
        } else {
            return null;
        }
    }

    /**
     * @param $example array
     * @return int x>=0
     */
    public function countByExample($example) {
        $queryBuilder = $this->getQBuilder();
        $queryBuilder = $queryBuilder->select("count(x)")
            ->from($this->getEntityName(), "x");

        foreach ($example as $property => $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq("x." . $property, ":" . $property))
                ->setParameter($property, $value);
        }

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findByIds($ids = array()) {
        if (Collections::isEmpty($ids)){
            return array();
        }

        $queryBuilder = $this->getQBuilder();
        $query = $queryBuilder->select("x")
            ->from($this->getEntityName(), "x")
            ->where($queryBuilder->expr()->in("x.id", $ids))
            ->getQuery();

        return $query->getResult();
    }

    public function remove($entity) {
        $this->getEm()->transactional(function($em) use ($entity){
            $em->remove($entity);
        });
    }

    public function removeAll(array $entities) {
        foreach ($entities as $entity) {
            $this->remove($entity);
        }
    }

    public function getPaginator(PaginationParams $paginationParams) {
        $qb = $this->getQBuilderByPaginatorParams($paginationParams);
        $qb = CriteriaHandler::handle($paginationParams->getCriteria(), $qb);
        return new Paginator($qb->getQuery(), true);
    }

    public function getQBuilderByPaginatorParams(PaginationParams $paginationParams) {
        $qb = $this->getQBuilder();
        $qb->select(CriteriaHandler::ROOT_ALIAS)
            ->from($this->getEntityName(), CriteriaHandler::ROOT_ALIAS)
            ->setFirstResult($paginationParams->getFirstResults())
            ->setMaxResults($paginationParams->getPageSize());

        if ($paginationParams->hasSorting()) {
            $sortingValue = $paginationParams->getSortingValue();
            if (Objects::isArray($sortingValue)) {
                foreach ($sortingValue as $sortVal => $orderType)
                    $qb->addOrderBy(CriteriaHandler::ROOT_ALIAS . "." . $sortVal, $orderType);
            } else {
                $qb->orderBy(CriteriaHandler::ROOT_ALIAS . "." . $sortingValue);
            }

        }
        return $qb;
    }

}
