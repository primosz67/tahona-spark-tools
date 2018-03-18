<?php
/**
 *
 * 
 * Date: 29.06.15
 * Time: 17:15
 */

namespace Spark\Persistence\Criteria;


class JoinTableCriteria implements Criteria {


    /**
     * @var
     */
    private $alias;
    /**
     * @var
     */
    private $property;

    function __construct($alias, $property) {
        $this->alias = $alias;
        $this->property = $property;
    }

    /**
     * @return mixed
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getProperty() {
        return $this->property;
    }



}