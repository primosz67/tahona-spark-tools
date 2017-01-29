<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.06.15
 * Time: 13:40
 */

namespace spark\form\converter;


use spark\utils\Objects;
use spark\utils\StringUtils;

class ToBooleanConverter implements DataConverter {

    public function convert($obj, $value) {
        if (Objects::isNotNull($value)) {
            return StringUtils::equalsIgnoreCase("true", $value);
        }
        return false;
    }
}