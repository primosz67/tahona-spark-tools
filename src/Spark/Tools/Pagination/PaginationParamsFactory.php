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

    public static function create(int $currentPage = 1, int $pageSize = 20, Criteria $criteria = null, $sortingValue = null): PaginationParams {
        $paginationParams = new PaginationParams();
        $paginationParams->setPageSize($pageSize);
        $paginationParams->setCriteria($criteria);

        $paginationParams->setCurrentPage($currentPage);
        return $paginationParams;
    }

}