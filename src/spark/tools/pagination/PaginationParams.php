<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 03.08.14
 * Time: 14:53
 */

namespace spark\tools\pagination;


use spark\persistence\crud\CrudService;
use spark\table\pagination\Pagination;
use spark\utils\Collections;
use spark\utils\StringUtils;

class PaginationParams extends Pagination{

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