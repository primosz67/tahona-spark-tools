<?php
/**
 * 
 * User: crownclown67
 * Date: 23.05.17
 * Time: 08:08
 */

namespace Spark\Tools\Table\Pagination;


class Paginator {


    private $count;
    private $result;

    /**
     * @var PaginationParams
     */
    private $paginatorParams;

    private $isInitiated = false;

    private $pagesNumbers;
    private $pagesCount = 10;

    function __construct(Pagination $paginatorParams, $result = array(), $count) {
        $this->paginatorParams = $paginatorParams;
        $this->count = $count;
        $this->result = $result;
    }

    /**
     * @return \ArrayIterator
     */
    public function getResults() {
        $this->init();
        return $this->result;
    }

    private function init() {
        if (false == $this->isInitiated) {
            $this->isInitiated = true;
            $this->pagesNumbers = $this->buildPagesNumber();
        }
    }

    public function getPages() {
        $this->init();
        return $this->pagesNumbers;
    }

    private function buildPagesNumber() {
        $pageRangeSizeToShow = $this->getLastPageNr();
        $pageRangeSizeToShow = $this->pagesCount < $pageRangeSizeToShow ? $this->pagesCount : $pageRangeSizeToShow;

        $startPageNr = $this->getStartPageNr($pageRangeSizeToShow);
        $endPageNr = $this->getEndPageNumber($pageRangeSizeToShow);

        $pages = array();
        for ($i = $startPageNr; $i <= $endPageNr; $i++) {
            $pages[] = $i;
        }

        return $pages;
    }

    public function getCurrentPage() {
        return $this->paginatorParams->getCurrentPage();
    }

//
    public function getNextPage() {
        $page = $this->paginatorParams->getCurrentPage();
        if ($this->hasNextPage()) {
            return $page + 1;
        } else {
            return $page;
        }
    }

    public function getPreviousPage() {
        $page = $this->paginatorParams->getCurrentPage();
        if ($this->hasPreviousPage()) {
            return $page - 1;
        } else {
            return $page;
        }
    }

    public function hasNextPage() {
        $currentPage = $this->paginatorParams->getCurrentPage();
        return $currentPage + 1 <= $this->getLastPageNr();
    }

    public function getPageSize() {
        return $this->paginatorParams->getPageSize();
    }

    /**
     * @return float
     */
    public function getLastPageNr() {
        $count = $this->count;
        $pageSize = $this->paginatorParams->getPageSize();
        if ($count > $pageSize) {
            return ceil($count / $this->paginatorParams->getPageSize());
        }
        return 1;
    }

    private function getFirstPageNr() {
        return 1;
    }

    /**
     * @param $pageRangeSizeToShow
     * @return int
     */
    private function getStartPageNr($pageRangeSizeToShow) {
        $endPageNumber = $this->getEndPageNumber($pageRangeSizeToShow);
        $currentPage = $this->getCurrentPage();

        $paginationLeftSideSize = (int)($pageRangeSizeToShow - ($currentPage - $endPageNumber));

        $startPageNr = (int)$currentPage - $paginationLeftSideSize;
        if ($startPageNr < 1) {
            $startPageNr = 1;
            return $startPageNr;
        }
        return $startPageNr;
    }

    /**
     * @param $pageRangeSizeToShow
     * @return float|int
     */
    private function getEndPageNumber($pageRangeSizeToShow) {
        $rightSide = (int)(($this->paginatorParams->getCurrentPage() + $pageRangeSizeToShow));
        $endPageNr = (int)$this->paginatorParams->getCurrentPage() + $rightSide;
        if ($endPageNr > $this->getLastPageNr()) {
            $endPageNr = $this->getLastPageNr();
            return $endPageNr;
        }
        return $endPageNr;
    }

    public function hasPreviousPage() {
        $page = $this->getCurrentPage();
        return $page - 1 > 0;
    }

    public function getResultsCount() {
        return $this->count;
    }
}