<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 01.10.16
 * Time: 21:11
 */

namespace spark\tools\search;


use spark\persistence\criteria\Criterias;
use spark\persistence\criteria\Junction;
use spark\http\Request;
use spark\utils\BooleanUtils;
use spark\utils\Collections;
use spark\utils\Objects;

class SearchFilterHelper {

    /**
     * @var Request
     */
    private $params;
    /**
     * @var Junction
     */
    private $junction;

    /**
     * SearchFilterHelper constructor.
     * @param Request $request
     */
    private function __construct(Junction $junction, $params = array()) {
        $this->params = $params;
        $this->junction = $junction;
    }

    public static function createOr($request = null) {
        if (Objects::isNull($request)) {
            return new SearchFilterHelper(Criterias::orCri());
        }
        return new SearchFilterHelper(Criterias::orCri(), $request->getAllParams());
    }


    public static function createAnd($request = null) {
        if (Objects::isNull($request)) {
            return new SearchFilterHelper(Criterias::andCri());
        }
        return new SearchFilterHelper(Criterias::andCri(), $request->getAllParams());
    }

    /**
     * @param $field
     * @return SearchFilterHelper
     */
    public function addText($field, $value = null) {

        $param = Collections::getValue($this->params, $field, $value);
        if (Objects::isNotNull($param)) {
            $this->junction->addCriteria(Criterias::like($field, $param));
        }
        return $this;
    }

    /**
     * @param $field
     * @return SearchFilterHelper
     */
    public function addBool($field, $value = null) {
        $param = Collections::getValue($this->params, $field, $value);
        if (Objects::isNotNull($param)) {
            $this->junction->addCriteria(Criterias::eq($field, BooleanUtils::toNumber($param)));
        }
        return $this;
    }

    /**
     * @return Junction
     */
    public function get() {
        return $this->junction;
    }
}