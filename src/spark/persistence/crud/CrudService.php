<?php

namespace spark\persistence\crud;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use spark\common\Optional;
use spark\core\service\ServiceHelper;
use spark\tools\pagination\PaginationParams;
use spark\tools\pagination\SimplePagination;
use spark\utils\Asserts;
use spark\utils\Collections;

/**
 * Class CrudService
 * @package spark\persistence\crud
 */
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


    /**
     * @param $id
     * @return
     */
    public function getById($id) {
        $byId = $this->find($id);
        return $byId->orElseThrow(new EntityNotFoundException());
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
     * @throws \spark\common\IllegalArgumentException
     */
    public function findOne($example = array()) {
        Asserts::checkArgument(Collections::isNotEmpty($example), "Example must not be empty.");
        return Optional::ofNullable($this->getDAO()->getOneByExample($example));
    }

    public function getOneByExample($example) {
        return $this->getDAO()->getOneByExample($example);
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
     * @param PaginationParams $paginationParams
     * @return SimplePagination
     */
    public function getPagination(PaginationParams $paginationParams) {
        $paginator = $this->getDAO()->getPaginator($paginationParams);
        return new SimplePagination($paginator, $paginationParams);
    }

}
