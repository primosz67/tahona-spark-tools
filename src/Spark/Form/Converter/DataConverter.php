<?php
/**
 *
 *
 * Date: 28.06.15
 * Time: 13:39
 */

namespace Spark\Form\Converter;


interface DataConverter {
    public function convert($obj, $value);
}