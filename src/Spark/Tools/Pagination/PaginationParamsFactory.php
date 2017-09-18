<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.08.14
 * Time: 12:35
 */

namespace Spark\Tools\Pagination;


use Doctrine\ORM\Query;
use Spark\Persistence\Criteria\Criteria;

class PaginationParamsFactory {

    /**
     * @param $currentPage
     * @param $pageSize
     * @param Criteria $criteria
     * @return PaginationParams
     */
    public static function create(int $currentPage = 1, int $pageSize = 20, Criteria $criteria = null, $sortingValue = null) {
        $paginationParams = new PaginationParams();
        $paginationParams->setPageSize((int)$pageSize);
        $paginationParams->setCriteria($criteria);

        $paginationParams->setCurrentPage((int)$currentPage);
        return $paginationParams;
    }


}