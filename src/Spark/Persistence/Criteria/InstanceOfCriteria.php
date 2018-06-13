<?php
/**
 *
 * 
 * Date: 05.08.14
 * Time: 06:45
 */

namespace Spark\Persistence\Criteria;


class InstanceOfCriteria implements Criteria {

    private $value;
    private $alias;

    function __construct($likeValue = "", $alias="") {
        $this->value = $likeValue;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }




    public function getAlias() {
        return $this->alias;
    }


}