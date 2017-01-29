<?php

namespace spark\persistence\crud;

use Doctrine\Common\Collections\Collection;
use spark\common\Optional;
use spark\tools\pagination\PaginationFactory;
use spark\tools\pagination\PaginationParams;
use spark\core\service\ServiceHelper;

class CrudService extends ServiceHelper {

    /**
     *
     * @var CrudDao
     */
    private $dao;

    public function __construct($dao = null) {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @return Optional
     */
    public function find($id) {
        return Optional::ofNullable($this->getDAO()->findById($id));
    }

    public function getById($id) {
        return $this->getDAO()->findById($id);
    }

    /**
     * @return CrudDao
     */
    protected function getDAO() {
        return $this->dao;
    }

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
     * @return Collection
     */
    public function findByExample($example = array(), $orderBy = array()) {
        return $this->getDAO()->findByExample($example, $orderBy);
    }

    public function findOneByExample($example) {
        return $this->getDAO()->findOneByExample($example);
    }

    public function countByExample($example = array()) {
        return $this->getDAO()->countByExample($example);
    }

    public function findByIds($ids = array()) {
        if (count($ids) > 0) {
            return $this->getDAO()->findByIds($ids);
        } else {
            return array();
        }
    }

    public function remove($entity) {
        $this->getDAO()->remove($entity);
    }

    public function removeAll(array $entities) {
        $this->getDAO()->removeAll($entities);
    }

    /**
     * @deprecated
     * @param PaginationParams $paginator
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getPaginator(PaginationParams $paginator) {
        return $this->getDAO()->getPaginator($paginator);
    }

    /**
     * @param PaginationParams $paginationParams
     * @return pagination\SimplePagination
     */
    public function getPagination(PaginationParams $paginationParams) {
        return PaginationFactory::simplePagination($paginationParams, $this);
    }

}
