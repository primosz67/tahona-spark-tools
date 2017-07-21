<?php


namespace spark\test;


use spark\utils\Asserts;

class Verify {
    public static function once() {
        return new Verifier(function ($a) {
            /** @var Answer $a */
            $count = $a->getValueCallCount();
            Asserts::checkState($count === 1, $a->getMethod() . " should called only once! ($count)");
        });
    }

    public static function times($times) {
        return new Verifier(function ($a) use ($times) {
            /** @var Answer $a */
            $count = $a->getValueCallCount();
            Asserts::checkState($count === $times, $a->getMethod() . " should called $times times! ($count)");
        });
    }

    public static function atLeastOnce() {
        return new Verifier(function ($a) {
            /** @var Answer $a */
            $count = $a->getValueCallCount();
            Asserts::checkState($count >= 1, $a->getMethod() . " should called once or more! ($count)");
        });
    }

    public static function max($times) {
        return new Verifier(function ($a) use ($times){
            /** @var Answer $a */
            $count = $a->getValueCallCount();
            Asserts::checkState($count <= $times, $a->getMethod() . " should called maximal $times times! ($count)");
        });
    }

}