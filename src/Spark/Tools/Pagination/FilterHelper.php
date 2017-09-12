<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.06.15
 * Time: 19:25
 */

namespace Spark\Tools\Pagination;


use Spark\Tools\Pagination\SimplePagination;
use Spark\Utils\UrlUtils;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\Objects;

class FilterHelper {

    private $path;
    private $current;
    private $sorting;
    private $params = array();

    public function addParams($params = array()) {
        Asserts::checkArgument(Collections::isNotEmpty($params));
        $this->params = $params;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function getPageUrl($page) {
        return $this->getUrlWithParam("page", $page);
    }

    public function getSortingUrl($sorting) {
        return $this->getUrlWithParam("sorting", $sorting);
    }

    public function  getPreviousUrl() {
        return $this->getPageUrl($this->current - 1);
    }

    public function  getNextUrl() {
        return $this->getPageUrl($this->current + 1);
    }

    /**
     * @param mixed $current
     */
    public function setCurrent($current) {
        $this->current = $current;
    }

    /**
     * @param mixed $sorting
     */
    public function setSorting($sorting) {
        $this->sorting = $sorting;
    }

    public function setPagination(SimplePagination $pagination) {
        $this->current = $pagination->getCurrentPage();
    }

    public function  getUrlWithParam($key, $value) {
        Asserts::checkArgument(false === Objects::isArray($value));

        $params = $this->getParams();
        $params[$key] = $value;

        $url = UrlUtils::appendParams($this->path, $params);
        return $url;
    }

    public function getUrl() {
        return UrlUtils::appendParams($this->path, $this->params);
    }

    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }



    /**
     * @return array
     */
    public function getParams() {
        $params = array();
        Collections::addAllOrReplace($params, $this->params);
        $params["sorting"] = $this->sorting;
        $params["page"] = $this->current;
        return $params;
    }


}