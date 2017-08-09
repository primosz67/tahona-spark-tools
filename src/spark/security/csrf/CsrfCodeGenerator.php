<?php


namespace spark\security\csrf;


use spark\security\utils\PassUtils;
use spark\utils\Objects;
use spark\utils\UrlUtils;

final class CsrfCodeGenerator {


    public static function generate() {
        return PassUtils::genCode(8);
    }

    public static function getSessionCode($code, $url = null) {
        if (Objects::isNotNull($code)) {

            if (Objects::isNull($url)) {
                $url = UrlUtils::getCurrentUrl();
            }

            return $url . $code;
        }
        return null;
    }

}