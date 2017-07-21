<?php

namespace spark\test;


use spark\core\library\Annotations;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\ReflectionUtils;

class Mocker {

    /**
     * @param $answer
     * @return Answer
     */
    public static function given($answer) {
        return $answer;
    }

    public static function initMocks($bean, $mocks) {
        ReflectionUtils::handlePropertyAnnotation($bean, Annotations::INJECT,
            function ($bean, $reflectionProperty, $annotation) use ($mocks) {

                $mock = new Mock();
                /** @var \ReflectionProperty $reflectionProperty */
                if (Collections::hasKey($mocks, $reflectionProperty->getName())) {
                    $mock = $mocks[$reflectionProperty->getName()];
                }

                Mocker::record($mock);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($bean, $mock);
            });
    }

    public static function verify($mock, Verifier $verifier = null) {
        Asserts::checkArgument($mock instanceof Mock, "Should be instance of Mock class");

        if (Objects::isNull($verifier)) {
            $verifier = Verify::once();
        }
        Collections::builder()
            ->addAll($mock->getAnswers())
            ->each(function ($a) use ($verifier) {
                /** @var Answer $a */
                $verifier->verify($a);
            });
    }

    /**
     * @param $mock
     * @return object
     */
    public static function called($mock) {
        return $mock;
    }
    public static function record(Mock $mock) {
        $mock->stop();
        return $mock;
    }
}
