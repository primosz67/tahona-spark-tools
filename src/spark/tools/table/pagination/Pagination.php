<?php

namespace spark\tools\table\pagination;

use spark\tools\pagination\Sorting;
use spark\utils\StringUtils;

class Pagination {

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