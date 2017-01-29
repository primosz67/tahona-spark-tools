<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 03.08.14
 * Time: 14:53
 */

namespace spark\tools\pagination;


use spark\persistence\crud\CrudService;
use spark\utils\Collections;
use spark\utils\StringUtils;

class PaginationParams {

    private $criteria;

    private $currentPage = 1;
    private $pageSize = 20;

    private $sortingValue = array();

    public function getFirstResults() {
        return ($this->currentPage * $this->pageSize) - $this->pageSize;
    }

    public function hasSorting() {
        return StringUtils::isNotBlank($this->sortingValue);
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria) {
        $this->criteria = $criteria;
    }

    /**
     * @return mixed
     */
    public function getCriteria() {
        return $this->criteria;
    }


    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }

    /**
     *
     * array (property=> ASC)
     *
     * @param mixed $sortingValue array or property
     */
    public function setSortingValue($sortingValue) {
        $this->sortingValue = $sortingValue;
    }

    public function addSorting($property, Sorting $sorting) {
        $this->sortingValue[$property] = $sorting->getSort();
    }

    public function getSortingValue() {
        return $this->sortingValue;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }


}