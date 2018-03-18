<?php


namespace Spark\Security\Csrf;


use Spark\Security\Utils\PassUtils;
use Spark\Utils\Objects;
use Spark\Utils\UrlUtils;

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