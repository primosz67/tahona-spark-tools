<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.08.14
 * Time: 12:35
 */

namespace spark\tools\pagination;


use Doctrine\ORM\Query;
use spark\persistence\criteria\Criteria;

class PaginationParamsFactory {

    /**
     * @param $currentPage
     * @param $pageSize
     * @param Criteria $criteria
     * @return PaginationParams
     */
    public static function create($currentPage, $pageSize, Criteria $criteria = null, $sortingValue = null) {
        $paginationParams = new PaginationParams();
        $paginationParams->setPageSize((int)$pageSize);
        $paginationParams->setCriteria($criteria);
        $paginationParams->setSortingValue($sortingValue);
        $paginationParams->setCurrentPage((int)$currentPage);
        return $paginationParams;
    }



}