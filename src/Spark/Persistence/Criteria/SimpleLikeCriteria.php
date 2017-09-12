<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 05.08.14
 * Time: 06:45
 */

namespace Spark\Persistence\Criteria;


class SimpleLikeCriteria extends EqCriteria {


    /**
     * @return mixed
     */
    public function getLikeValue() {
        return $this->getValue();
    }

    public function getValue() {
        return "%" . parent::getValue() . "%";
    }


}