<?php
/**
 *
 * 
 * Date: 03.08.14
 * Time: 14:53
 */

namespace Spark\Tools\Pagination;


use Spark\Persistence\Crud\CrudService;
use Spark\Tools\Table\Pagination\Pagination;
use Spark\Utils\Collections;
use Spark\Utils\StringUtils;

class PaginationParams extends Pagination {

    private $criteria;


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

}