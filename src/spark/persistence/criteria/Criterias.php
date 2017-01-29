<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 11.11.15
 * Time: 12:58
 */

namespace spark\persistence\criteria;


use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\StringUtils;

class Criterias {

    public static function builder(){
        return new CriteraBuilder();
    }

    public static function orCri($criterias = array()) {
        Asserts::isArray($criterias);
        return new OrCriteria($criterias);
    }

    public static function andCri($criterias = array()) {
        Asserts::isArray($criterias);
        return new AndCriteria($criterias);
    }

    public static function like($property, $value, $alias = "") {
        if (StringUtils::contains($property, ".")) {
            $splited = StringUtils::split($property, ".");
            $newProperty = $splited[Collections::size($splited) - 1];
            $neAlias= $splited[0];
            return new SimpleLikeCriteria($newProperty, $value, $neAlias);
        }
        return new SimpleLikeCriteria($property, $value, $alias);
    }

    public static function eq($property, $value, $alias = "") {
        return new EqCriteria($property, $value, $alias);
    }

    /**
     * @param $rootAlias CriteriaHandler::ROOT
     * @param $property
     * @param $newAlias
     * @return JoinTableCriteria
     */
    public static function join($property, $newAlias, $rootAlias = CriteriaHandler::ROOT_ALIAS) {
        return new JoinTableCriteria($newAlias, StringUtils::join(".", array($rootAlias, $property)));
    }


}