<?php

namespace spark\tools\url;

use spark\utils\Collections;
use spark\utils\Functions;
use spark\utils\Predicates;
use spark\utils\StringFunctions;
use spark\utils\StringUtils;

class Url {

    private $scheme;
    private $port;
    private $host;
    private $path;
    private $queryParams;

    /**
     * Url constructor.
     */
    public function __construct($url) {
        $arr = parse_url($url);

        $this->scheme = $arr["scheme"];
        $this->host = $arr["host"];
        $this->path = Collections::getValueOrDefault($arr, "path","");
        $this->queryParams = Collections::getValueOrDefault($arr, "query", array());
        $this->queryParams = $this->parseQuery($this->queryParams);

    }

    public function build() {
        $aar = array(
            $this->scheme,
            "://",
            $this->host,
            $this->getPort(),
            $this->path,
            $this->getQuery()
        );

        $res = Collections::filter($aar, Predicates::notNull());
        return StringUtils::join("", $res);
    }

    public static function parse($url) {
        return new Url($url);
    }

    private function getQuery() {
        if (Collections::isNotEmpty($this->queryParams)) {
            $values = array();
            foreach ($this->queryParams as $k=> $q) {
                $values[]=$k."=".$q;
            }

            return "?" . StringUtils::join("&", $values);
        }
        return null;
    }

    private function getPort() {
        if (StringUtils::isNotBlank($this->port)) {
            return ":" . $this->port;
        }
        return null;
    }

    private function parseQuery($query) {
        $queries = StringUtils::split($query, "&");
        $res = array();

        foreach ($queries as $q) {
            $keyValue = StringUtils::split($q, "=");
            $res[$keyValue[0]] = $keyValue[1];
        }
        return $res;
    }

    /**
     * @param $queryKey
     * @return $this
     */
    public function removeQueryParam($queryKey)  {
        Collections::removeByKey($this->queryParams, $queryKey);
        return $this;
    }

}