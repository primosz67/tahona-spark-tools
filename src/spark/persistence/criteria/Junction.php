<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 29.06.15
 * Time: 18:28
 */

namespace spark\persistence\criteria;


use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Objects;
use spark\utils\Predicates;

abstract class Junction {

    private $cri = [];

    function __construct($cri = array()) {
        $this->cri = $cri;
    }


    public function getCriteris() {
        return $this->cri;
    }

    public function addCriteria(Criteria $criteria) {
        $this->cri[] = $criteria;
    }

    public function addAllCriteria($criteria=array()) {
        $criterias = Collections::builder($criteria)
            ->filter(Predicates::notNull())
            ->get();

        Collections::addAll($this->cri, $criterias);
    }

}