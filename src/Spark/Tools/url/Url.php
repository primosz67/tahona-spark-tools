<?php

namespace Spark\Tools\url;

use Spark\Common\Optional;
use Spark\Utils\Collections;
use Spark\Utils\FileUtils;
use Spark\Utils\Functions;
use Spark\Utils\Predicates;
use Spark\Utils\StringFunctions;
use Spark\Utils\StringUtils;

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

        $this->scheme = $arr['scheme'];
        $this->host = $arr['host'];
        $this->path = Collections::getValueOrDefault($arr, 'path', '');
        $this->queryParams = $this->parseQuery($this->getQueryString($arr));

    }

    public function build() {
        $aar = array(
            $this->scheme,
            '://',
            $this->host,
            $this->getPort(),
            $this->path,
            $this->getQuery()
        );

        $res = Collections::filter($aar, Predicates::notNull());
        return StringUtils::join('', $res);
    }

    public static function parse($url) {
        return new Url($url);
    }

    private function getQuery() {
        if (Collections::isNotEmpty($this->queryParams)) {
            $values = array();
            foreach ($this->queryParams as $k => $q) {
                $values[] = $k . '=' . $q;
            }
            return '?' . StringUtils::join('&', $values);
        }
        return null;
    }

    private function getPort() {
        if (StringUtils::isNotBlank($this->port)) {
            return ':' . $this->port;
        }
        return null;
    }

    private function parseQuery(string $query=null) {

        if (StringUtils::isNotBlank($query)) {
            $queries = StringUtils::split($query, '&');
            $res = array();

            foreach ($queries as $q) {
                $keyValue = StringUtils::split($q, '=');
                $res[$keyValue[0]] = Collections::getValueOrDefault($keyValue, 1, '');
            }
            return $res;
        }
        return array();
    }

    /**
     * @param $queryKey
     * @return $this
     */
    public function removeQueryParam($queryKey) {
        Collections::removeByKey($this->queryParams, $queryKey);
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addParam($key, $value) {
        $this->queryParams[$key] = $value;
        return $this;
    }

    private function getQueryString($arr){
        return Optional::ofNullable($arr)
            ->map(Functions::getArrayValue('query'))
            ->orElse('');
    }

    public function getPath() {
        return $this->path;
    }

}