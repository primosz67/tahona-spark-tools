<?php
/**
 *
 *
 * Date: 28.11.16
 * Time: 21:53
 */

namespace Spark\Persistence\Criteria;


use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

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
     * @param $fpath
     * @param $alias
     * @return $this CriteraBuilder
     */
    public function join($fpath, $alias) {
        $this->alias = $alias;
        $this->criteria[] = Criterias::join($fpath, $alias);
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

    public function instanceOf ($class) {
        $this->criteria[] = Criterias::instanceOf($class, $this->getAlias());
        return $this;
    }
}