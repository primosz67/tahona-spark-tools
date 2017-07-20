<?php


namespace spark\test;


use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;

class Mock {
    private $record;

    private $answers = array();


    function __call($methodName, $arguments) {
        $answer = Collections::getValue($this->answers, $methodName);

        if (Objects::isNotNull($answer)) {
            return $answer->getValue();
        }

        if ($this->record) {
            $this->answers[$methodName] = new Answer($methodName, $arguments);
            return $this->answers[$methodName];

        } else {
            return new Mock();
        }
    }

    public static function create() {
        $mock = new Mock();
        $mock->record = true;
        return $mock;
    }

    /**
     * @return array
     */
    public function getAnswers() {
        return $this->answers;
    }


}