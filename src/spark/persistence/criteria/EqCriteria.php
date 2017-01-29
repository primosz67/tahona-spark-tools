<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 05.08.14
 * Time: 06:45
 */

namespace spark\persistence\criteria;


class EqCriteria implements Criteria {

    private $property;
    private $value;
    private $alias;

    function __construct($property, $likeValue = "", $alias="") {
        $this->property = $property;
        $this->value = $likeValue;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }




    /**
     * @return mixed
     */
    public function getProperty() {
        return $this->property;
    }

    public function getAlias() {
        return $this->alias;
    }


}