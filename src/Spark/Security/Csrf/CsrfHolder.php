<?php


namespace Spark\Security\Csrf;


use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;
use Spark\Utils\UrlUtils;

class CsrfHolder {

    private $map = array();

    public function getCode($url): string {
        $code = CsrfCodeGenerator::generate();
        $this->map[$url] = $code;
        return $code;
    }

    public function isValid($csrf): bool {
        $isValid = $this->checkIsValid($csrf);
        if ($isValid) {
            $this->removeCurrentCsrf();
        }
        return $isValid;
    }

    private function checkIsValid($csrf): bool {
        $url = UrlUtils::getCurrentUrl();

        if (StringUtils::isNotBlank($csrf) || Collections::hasKey($this->map, $url)) {
            return StringUtils::equals($csrf, $this->map[$url]);
        }

        return false;
    }

    private function removeCurrentCsrf(): void {
        Collections::removeByKey($this->map, UrlUtils::getCurrentUrl());
    }
}