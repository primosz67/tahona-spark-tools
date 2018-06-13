<?php
/**
 *
 *
 * Date: 05.08.14
 * Time: 06:47
 */

namespace Spark\Persistence\Criteria;


use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class CriteriaHandler {

    const ROOT_ALIAS = "x";

    public static function handle(Criteria $cr = null, QueryBuilder &$qb = null) {
        if (isset($cr) && isset($qb)) {
            $parameters = self::handleCriteria($cr, $qb);
            self::setParameters($qb, $parameters);
        }
        return $qb;
    }

    /**
     * @param Criteria $cr
     * @param QueryBuilder $qb
     * @return array parameters
     */
    private static function handleCriteria(Criteria $cr, QueryBuilder &$qb) {
        $parameters = array();

        $qb = self::handleJoinTable($cr, $qb);
        self::addExpr($qb, self::handleSimpleLike($cr, $qb, $parameters));
        self::addExpr($qb, self::handleJunctionAsSubQuery($cr, $qb, $parameters));
        return $parameters;
    }

    private static function handleJoinTable($cr, QueryBuilder &$qb) {
        if ($cr instanceof JoinTableCriteria) {
            /** @var $criteria JoinTableCriteria */
            $criteria = $cr;
            $qb->join($criteria->getProperty(), $criteria->getAlias());
        }
        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param $expr Composite
     */
    private static function addExpr(QueryBuilder &$qb, $expr) {
        if ($expr != null) {
            $qb->andWhere($expr);
        }
    }

    /**
     * @param Criteria $cr
     * @param QueryBuilder $qb
     */
    private static function handleSimpleLike(Criteria $criteria, QueryBuilder &$qb, &$parameters) {
        if ($criteria instanceof EqCriteria) {
            /** @var $criteria EqCriteria */
            $value = $criteria->getValue();

            if (Objects::isNotNull($value)) {
                if (!(Objects::isString($value))) {
                    $index = self::addParam($parameters, $criteria);
                    return self::addEqExpression($qb, $criteria, $index);

                } else if (StringUtils::isNotBlank(StringUtils::replace($value, "%", ""))) {
                    $index = self::addParam($parameters, $criteria);
                    return self::addLikeExpression($qb, $criteria, $index);
                }
            }
        } else if ($criteria instanceof InstanceOfCriteria) {
            /** @var $criteria InstanceOfCriteria */
            $value = $criteria->getValue();
            if (Objects::isNotNull($value)) {
                return $qb->expr()->isInstanceOf(self::ROOT_ALIAS, $criteria->getValue());

            }
        }
        return null;
    }


    /**
     * @param $parameters
     * @param $criteria
     * @return array
     */
    private static function addParam(&$parameters, EqCriteria $criteria) {
        $property = $criteria->getProperty();
        if (!Collections::hasKey($parameters, $property)) {
            $parameters[$property] = array();
        }
        $size = Collections::size($parameters[$property]);
        $parameters[$property][] = $criteria->getValue();
        return $size;
    }

    /**
     * @param QueryBuilder $qb
     * @param $criteria
     * @return \Doctrine\ORM\Query\Expr\Comparison
     */
    private static function addEqExpression(QueryBuilder &$qb, $criteria, $index = 0) {
        return $qb->expr()->eq(self::createProperty($criteria), ":" . $criteria->getProperty() . "_" . $index);
    }

    /**
     * @param $criteria
     * @return string
     */
    private static function createProperty(EqCriteria $criteria) {
        $alias = self::ROOT_ALIAS;
        if (StringUtils::isNotBlank($criteria->getAlias())) {
            $alias = $criteria->getAlias();
        }

        return StringUtils::join(".", array($alias, $criteria->getProperty()));
    }

    /**
     * @param QueryBuilder $qb
     * @param $criteria
     * @return \Doctrine\ORM\Query\Expr\Comparison
     */
    private static function addLikeExpression(QueryBuilder &$qb, $criteria, $index = 0) {
        return $qb->expr()->like(self::createProperty($criteria), ":" . $criteria->getProperty() . "_" . $index);
    }

    /**
     * @param $cr
     * @param $qb QueryBuilder::
     * @return mixed
     */
    private static function handleJunctionAsSubQuery($cr, &$qb, &$parameters) {
        if ($cr instanceof Junction) {

            /** @var $criteria Junction */
            $criteria = $cr;

            $criteriaList = $criteria->getCriteris();

            $junction = self::getJunction($qb, $criteria);

            foreach ($criteriaList as $subCriteria) {
                if ($subCriteria instanceof JoinTableCriteria) {
                    self::handleJoinTable($subCriteria, $qb);
                } else if ($subCriteria instanceof Junction) {
                    $junction->add(self::handleJunctionAsSubQuery($subCriteria, $qb, $parameters));
                } else {
                    $expr = self::handleSimpleLike($subCriteria, $qb, $parameters);
                    if ($expr != null) {
                        $junction->add($expr);
                    }
                }
            }

            if ($junction->count() > 0) {
                return $junction;
            }

        }
        return null;
    }

    /**
     * @param $qb
     * @param $criteria
     * @return Composite
     */
    private static function getJunction(&$qb, $criteria) {
        $isAnd = $criteria instanceof AndCriteria;

        if ($isAnd) {
            /** @var $junction Composite */
            $junction = $qb->expr()->andX();
            return $junction;
        } else {
            $junction = $qb->expr()->orX();
            return $junction;
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param $parameters
     */
    private static function setParameters(QueryBuilder &$qb, $parameters) {
        foreach ($parameters as $key => $v) {
            if (Objects::isArray($v)) {
                foreach ($v as $index => $value) {
                    $qb->setParameter($key . "_" . $index, $value);
                }
            } else {
                $qb->setParameter($key, $v);
            }
        }
    }

    private static function handleCriterias(Criteria $cr, QueryBuilder &$qb, $isAnd) {
        $qb = self::handleJoinTable($cr, $qb);
        self::addExpr($qb, self::handleSimpleLike($cr, $qb));
        self::addExpr($qb, self::handleJunctionAsSubQuery($cr, $qb, $parameters));
        return $qb;
    }

} 