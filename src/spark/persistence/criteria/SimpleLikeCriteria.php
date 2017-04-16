<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 05.08.14
 * Time: 06:45
 */

namespace spark\persistence\criteria;


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