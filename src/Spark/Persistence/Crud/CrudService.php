<?php

namespace Spark\Persistence\Crud;

use Spark\Common\Optional;
use Spark\Persistence\exception\EntityNotFoundException;
use Spark\Tools\Pagination\PaginationParams;
use Spark\Tools\Pagination\SimplePagination;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;

/**
 * Class CrudService
 * @package Spark\persistence\crud
 */
abstract class CrudService {

    /**
     * @param $id
     * @return Optional
     */
    public function find($id): Optional {
        return Optional::ofNullable($this->getDAO()->findById($id));
    }

    /**
     * @param $id
     * @return
     */
    public function getById($id) {
        $byId = $this->find($id);
        return $byId->orElseThrow(EntityNotFoundException::notFound());
    }

    /**
     * @return CrudDao
     */
    abstract protected function getDAO();

    /**
     * @deprecated
     * @param $id
     * @return object
     */
    public function findById($id) {
        return $this->getDAO()->findById($id);
    }

    public function save($entity) {
        $this->getDAO()->save($entity);
    }

    public function getAll() {
        return $this->getDAO()->getAll();
    }

    /**
     * @param array $example
     * @param array $orderBy
     * @return array
     */
    public function findByExample($example = array(), $orderBy = array(), $limit = null) {
        return $this->getDAO()->findByExample($example, $orderBy, $limit);
    }

    /**
     * @deprecated  use getOne
     * @param $example
     * @return null
     * @throws \Exception
     */
    public function findOneByExample($example) {
        return $this->getDAO()->getOneByExample($example);
    }

    /**
     * @param array $example
     * @return Optional
     * @throws \Exception
     * @throws \Spark\Common\IllegalArgumentException
     */
    public function findOne(array $example = array()): Optional {
        Asserts::checkArgument(Collections::isNotEmpty($example), 'Example must not be empty.');
        return Optional::ofNullable($this->getDAO()->getOneByExample($example));
    }

    public function getOneByExample($example) {
        return $this->getDAO()->getOneByExample($example);
    }

    public function countByExample(array $example = array()): int {
        return $this->getDAO()->countByExample($example);
    }

    public function findByIds(array $ids = array()): ?array {
        if (count($ids) > 0) {
            return $this->getDAO()->findByIds($ids);
        }
        return array();
    }

    public function remove($entity) {
        $this->getDAO()->remove($entity);
    }

    public function removeAll(array $entities) {
        $this->getDAO()->removeAll($entities);
    }

    /**
     * @param PaginationParams $paginationParams
     * @return SimplePagination
     */
    public function getPagination(PaginationParams $paginationParams) {
        $paginator = $this->getDAO()->getPaginator($paginationParams);
        return new SimplePagination($paginator, $paginationParams);
    }
}
