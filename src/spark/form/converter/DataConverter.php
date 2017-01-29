<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.06.15
 * Time: 13:39
 */

namespace spark\form\converter;


interface DataConverter {
    public function convert($obj, $value);
}