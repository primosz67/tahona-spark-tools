<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 03.12.16
 * Time: 20:12
 */

namespace Spark\Form\Converter;


use Spark\Utils\DateUtils;
use Spark\Utils\Objects;

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
            $dateTime = DateUtils::toDate($value, $this->format);

            if ($dateTime instanceof \DateTime) {
                return $dateTime;
            }
        }
        return null;
    }
}