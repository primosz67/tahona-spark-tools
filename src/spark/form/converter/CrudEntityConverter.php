<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 04.06.16
 * Time: 12:09
 */

namespace spark\form\converter;


use spark\persistence\crud\CrudService;
use spark\utils\Objects;

class CrudEntityConverter implements DataConverter {

    private $service;

    function __construct(CrudService $service) {
        $this->service = $service;
    }

    public function convert($obj, $value) {
        if (Objects::isNotNull($value)) {
            $id = (int)$value;
            if ($id > 0) {
                $optional = $this->service->find($value);
                return $optional->getOrNull();
            }
        }
        return null;
    }
} 