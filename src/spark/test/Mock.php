<?php


namespace spark\test;


use spark\common\IllegalStateException;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\JsonUtils;
use spark\utils\Objects;
use spark\utils\StringUtils;

class Mock {
    private $record = false;
    private $recorded = false;

    private $answers = array();


    function __call($methodName, $arguments) {

        if ($this->record) {
            $this->answers[$methodName] = new Answer($methodName, $arguments);
            return $this->answers[$methodName];

        } else {

            /** @var Answer $answer */

            $answerOp = Collections::builder()
                ->addAll($this->answers)
                ->findFirst(function ($x) use ($methodName, $arguments) {
                    return $this->match($x, $methodName, $arguments);
                });

            if ($answerOp->isPresent()) {
                return $answerOp->get()->getValue();
            }

            return new Mock();
        }
    }


    /**
     * @return array
     */
    public function getAnswers() {
        return $this->answers;
    }

    private function match(Answer $x, $methodName, $arguments) {
        return StringUtils::equals($x->getMethod(), $methodName)
        && $this->marchArgs($methodName, $x->getArguments(), $arguments);
    }

    private function marchArgs($methodName, $mockedArgs = array(), $realArgs = array()) {

        $i = 0;
        foreach ($mockedArgs as $mockArg) {
            $value = $realArgs[$i];
            if ($mockArg instanceof \Closure) {
                $res = $mockArg($value);
            } elseif (Objects::isArray($mockArg)) {
                $res = Objects::isArray($value)
                    && Collections::size($mockArg) === Collections::size($value)
                    && $this->marchArgs($methodName, $mockArg, $value);


            } else {
                $func = Matchers::eq($mockArg);
                $res = $func($value);
            }

            if (Objects::isNull($res)) {
                throw  new IllegalStateException("$methodName : No result. Missing matcher? ");
            }

            if (!$res) {
                throw  new IllegalStateException("$methodName : args not same:  a->" . JsonUtils::toJson($mockArg) . " b->" . JsonUtils::toJson($value));
            }
            $i++;
        }

        return true;
    }


    public static function create() {
        return new Mock();
    }

    /**
     * @return $this
     */
    public static function recordable() {
        $mock = new Mock();
        $mock->record = true;
        return $mock;
    }

    public function stop() {
        $this->record = false;
        $this->recorded = true;
    }


}