<?php

namespace spark\test;


use spark\common\IllegalStateException;
use spark\core\library\Annotations;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Functions;
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

                /** @var \ReflectionProperty $reflectionProperty */
                if (Collections::hasKey($mocks, $reflectionProperty->getName())) {
                    $mock = $mocks[$reflectionProperty->getName()];

                    Mocker::record($mock);
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($bean, $mock);
                }

            });
    }

    public static function verify($mock) {
        self::verifyWith($mock, Verify::once());
    }

    public static function verifyWith($mock, Verifier $verifier) {
        Asserts::checkArgument($mock instanceof Mock, "Should be instance of Mock class");
        Asserts::notNull($verifier, "Verifier is missing");


        Collections::builder()
            ->addAll($mock->getAnswers())
            ->each(function ($a) use ($verifier) {
                /** @var Answer $a */
                $verifier->verify($a);
            });


        //verify no more interaction

        /** @var Mock $mock */
        $notRecordedActionRegistry = $mock->getNotRecordedActionRegistry();

        $notRecordedRegister = Collections::builder()
            ->addAll($notRecordedActionRegistry)
            ->findFirst();

        $notRecordedRegister
            ->map(Functions::getArrayValue(0))
            ->ifPresent(function ($r) use ($mock) {
                $name = $mock->getName();
                throw  new IllegalStateException("Recordable mock was invoked but not recorded : $name->" . $r["methodName"]);
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
