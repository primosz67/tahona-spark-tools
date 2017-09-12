<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 03.08.14
 * Time: 15:36
 */

namespace Spark\Tools\Pagination;


use Doctrine\ORM\Tools\Pagination\Paginator;
use Spark\Persistence\Crud\CrudService;
use Spark\Utils\Objects;

class SimplePagination {

    private $count;
    private $result;

    /**
     * @var PaginationParams
     */
    private $paginatorParams;


    /**
     * @var Paginator
     */
    private $paginator;

    private $isInitiated = false;

    private $pagesNumbers;
    private $pagesCount = 10;

    public function __construct(Paginator $paginator, PaginationParams $paginatorParams) {
        $this->paginatorParams = $paginatorParams;
        $this->paginator = $paginator;
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

            $paginator = $this->paginator;

            $this->count = $paginator->count();
            $this->result = $paginator->getIterator();

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