<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.08.14
 * Time: 12:17
 */

namespace spark\tools\pagination;


class PaginationFactory {


    /**
     * @param PaginationParams $paginationParams
     * @param $service
     * @return SimplePagination
     */
    public static function simplePagination(PaginationParams $paginationParams, $service) {
        return new SimplePagination($service, $paginationParams);
    }




} 