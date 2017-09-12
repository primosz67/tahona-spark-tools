<?php


namespace Spark\Security\Csrf;


use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\UrlUtils;

class CsrfHolder {

    private $map = array();

    public function getCode($url) {
        $code = CsrfCodeGenerator::generate();

        $this->map[$url] = $code;
        return $code;
    }

    public function isValid($csrf) {
        $isValid = $this->checkIsValid($csrf);
        $this->map = array();
        return $isValid;
    }

    /**
     * @param $csrf
     * @return bool
     */
    private function checkIsValid($csrf) {
        $url = UrlUtils::getCurrentUrl();

        if (StringUtils::isBlank($csrf) || !Collections::hasKey($this->map, $url)) {
            return false;
        }

        return StringUtils::equals($csrf, $this->map[$url]);
    }
}