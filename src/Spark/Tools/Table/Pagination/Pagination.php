<?php

namespace Spark\Tools\Table\Pagination;

use Spark\Tools\Pagination\Sorting;
use Spark\Utils\Collections;
use Spark\Utils\StringUtils;

class Pagination {

    private $currentPage = 1;
    private $pageSize = 20;

    private $sortingValue = array();

    public function getFirstResults() {
        return ($this->currentPage * $this->pageSize) - $this->pageSize;
    }

    public function hasSorting() {
        return Collections::isNotEmpty($this->sortingValue);
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