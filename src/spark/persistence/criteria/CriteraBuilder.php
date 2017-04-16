<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.11.16
 * Time: 21:53
 */

namespace spark\persistence\criteria;


use spark\utils\Objects;
use spark\utils\StringUtils;

class CriteraBuilder {

    private $criteria = array();
    private $alias;

    /**
     * @return AndCriteria
     */
    public function asAnd() {
        $andCri = new AndCriteria();
        $andCri->addAllCriteria($this->criteria);
        return $andCri;
    }

    /**
     * @return OrCriteria
     */
    public function asOr() {
        $orCri = new OrCriteria();
        $orCri->addAllCriteria($this->criteria);
        return $orCri;
    }

    /**
     * @param $f
     * @param $al
     * @return $this CriteraBuilder
     */
    public function join($f, $al) {
        $this->alias = $al;
        $this->criteria[] = Criterias::join($f, $al);
        return $this;
    }


    /**
     * @param $property
     * @param $value
     * @return $this CriteraBuilder
     */
    public function like($property, $value) {
        if (StringUtils::isNotBlank($value)) {
            $this->criteria[] = Criterias::like($property, $value, $this->getAlias());
        }
        return $this;
    }

    /**
     * @return bool
     */
    private function getAlias() {
        return StringUtils::isBlank($this->alias)? "" : $this->alias;
    }
}