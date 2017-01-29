<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 03.12.16
 * Time: 20:12
 */

namespace spark\form\converter;


use spark\utils\DateUtils;
use spark\utils\Objects;

class DateConverter implements DataConverter{

    private $format;

    /**
     * DateConverter constructor.
     * @param $format
     */
    public function __construct($format) {
        $this->format = $format;
    }

    public function convert($obj, $value) {

        if (Objects::isNotNull($value))  {
            return DateUtils::toDate($value, $this->format);
        }
        return $value;
    }
}