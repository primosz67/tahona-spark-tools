<?php


namespace spark\security\csrf;


use spark\security\utils\PassUtils;
use spark\utils\Objects;
use spark\utils\UrlUtils;

final class CsrfCodeGenerator {


    public static function generate() {
        return PassUtils::genCode(8);
    }

    public static function getSessionCode($code) {
        if (Objects::isNotNull($code)) {
            return UrlUtils::getCurrentUrl() . $code;
        }
        return null;
    }

}