<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.06.15
 * Time: 13:40
 */

namespace spark\form\converter;


use spark\utils\Objects;
use spark\utils\StringUtils;

class FunctionConverter implements DataConverter {

    /**
     * @var \Closure
     */
    private $function;

    public function __construct(\Closure $function) {
        $this->function = $function;
    }

    public function convert($obj, $value) {
        $closure = $this->function;
        return $closure($value);
    }
}