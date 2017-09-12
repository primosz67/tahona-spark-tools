<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 07.09.16
 * Time: 22:14
 */

namespace Spark\Tools\Pagination;


class Sorting {

    const DESC = "DESC";
    const ASC = "ASC";
    private $sort;

    /**
     * Sorting constructor.
     */
    public function __construct($sort) {
        $this->sort = $sort;
    }


    public static function desc() {
        return new Sorting(self::DESC);
    }

    public static function asc() {
        return new Sorting(self::ASC);
    }

    /**
     * @return mixed
     */
    public function getSort() {
        return $this->sort;
    }

}