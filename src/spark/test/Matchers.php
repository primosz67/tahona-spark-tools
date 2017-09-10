<?php

namespace spark\test;


use spark\common\IllegalArgumentException;
use spark\core\library\Annotations;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\JsonUtils;
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
                $aRes = Matchers::objectToString($a);
                $bRes = Matchers::objectToString($b);

                throw  new IllegalArgumentException("Not same value! expected: $aRes got: $bRes");
            }
            return $res;
        };
    }

    private static function objectToString($a) {
        $json = JsonUtils::toJson($a);
        $suffix = self::getSuffix($json);
        $simpleClassName = Objects::getSimpleClassName($a);
        $objectHash = spl_object_hash($a);
        $jsonPart = StringUtils::substring($json, 0, 30);

        return $simpleClassName . "(" . $objectHash . ") " . $jsonPart . $suffix;
    }

    /**
     * @param $json
     * @return string
     */
    private static function getSuffix($json) {
        if (StringUtils::length($json) > 30) {
            return "...";
        }
        return " ";
    }


}
