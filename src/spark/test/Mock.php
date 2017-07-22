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

    private $recordedAnswers = [];
    private $notRecordedActionRegistry = [];
    private $name;

    /**
     * Mock constructor.
     */
    public function __construct($name) {
        $this->name = $name;
    }


    function __call($methodName, $arguments) {

        if ($this->record) {
            $this->recordedAnswers[$methodName] = new Answer($methodName, $arguments);
            return $this->recordedAnswers[$methodName];

        } else {

            /** @var Answer $answer */
            $answerOp = Collections::builder()
                ->addAll($this->recordedAnswers)
                ->findFirst(function ($x) use ($methodName, $arguments) {
                    return $this->match($x, $methodName, $arguments);
                });

            if ($answerOp->isPresent()) {
                return $answerOp->get()->getValue();
            }


            $this->registerNotRecordedAction($methodName, $arguments);
            return Mock::create();
        }
    }


    /**
     * @return array
     */
    public function getAnswers() {
        return $this->recordedAnswers;
    }

    private function match(Answer $x, $methodName, $arguments) {
        return StringUtils::equals($x->getMethod(), $methodName)
        && $this->matchArgs($methodName, $x->getArguments(), $arguments);
    }

    private function matchArgs($methodName, $mockedArgs = array(), $realArgs = array()) {

        $i = 0;
        foreach ($mockedArgs as $mockArg) {
            $value = $realArgs[$i];
            if ($mockArg instanceof \Closure) {
                $res = $mockArg($value);
            } elseif (Objects::isArray($mockArg)) {
                $res = Objects::isArray($value)
                    && Collections::size($mockArg) === Collections::size($value)
                    && $this->matchArgs($methodName, $mockArg, $value);


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


    public static function create($name = "Some Mock") {
        return new Mock($name);
    }

    /**
     * @return $this
     */
    public static function recordable($className) {
        $mock = new Mock($className);
        $mock->record = true;
        return $mock;
    }

    public function stop() {
        $this->record = false;
        $this->recorded = true;
    }

    /**
     * @param $methodName
     * @param $arguments
     */
    private function registerNotRecordedAction($methodName, $arguments) {
        if (Collections::hasKey($this->notRecordedActionRegistry, $methodName)) {
            $this->notRecordedActionRegistry[$methodName] = [];
        }

        $this->notRecordedActionRegistry[$methodName][] = [
            "methodName" => $methodName,
            "arguments" => $arguments
        ];
    }

    /**
     * @return array
     */
    function getNotRecordedActionRegistry() {
        return $this->notRecordedActionRegistry;
    }

    public function getName() {
        return $this->name;
    }

}