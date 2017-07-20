<?php


namespace spark\test;


use spark\utils\Objects;
use spark\utils\StringUtils;

class Answer {
    private $methodName;
    private $arguments;
    private $value;
    private $valueCallCount = 0;

    /**
     * CallReturn constructor.
     * @param $methodName
     * @param $arguments
     */
    public function __construct($methodName, $arguments) {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    public function is($methodName, $arguments) {
        return StringUtils::equals($methodName, $this->methodName);
    }

    public function getValue() {
        $this->valueCallCount +=1;
        return $this->value;
    }

    public function thenReturn($value) {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValueCallCount() {
        return $this->valueCallCount;
    }

    public function getMethod() {
        return $this->methodName;
    }


}