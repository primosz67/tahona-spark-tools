<?php

namespace spark\test;


use spark\common\IllegalArgumentException;
use spark\core\library\Annotations;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\ReflectionUtils;
use spark\utils\StringFunctions;
use spark\utils\StringUtils;

class Matchers {

    public static function any() {
        return function ($x) {
            return true;
        };
    }


    public static function instance($stringClass) {
        return function ($x) use ($stringClass) {
            $same = Collections::builder()
                ->addAll(Objects::getClassNames($x))
                ->anyMatch(StringFunctions::equals($stringClass));

            if (false == $same) {
                $className = Objects::getClassName($x);
                throw  new IllegalArgumentException("Not same instance! expected: $stringClass got: $className");
            }

            return $same;
        };
    }

    /**
     * @param $b
     * @return \Closure
     */
    public static function eq($b) {
        return function ($a) use ($b) {
            $res = Objects::equals($a, $b);
            if (!$res) {
                throw  new IllegalArgumentException("Not same value! expected: $a got: $b");
            }
            return $res;
        };
    }


}
