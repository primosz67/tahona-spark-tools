<?php


namespace spark\test;


use spark\utils\Asserts;

class Verify {
    public static function once() {
        return new Verifier(function ($a) {
            /** @var Answer $a */
            Asserts::checkState($a->getValueCallCount() === 1, $a->getMethod() . " called should called only once!");
        });
    }

    public static function times($times) {
        return new Verifier(function ($a, $times) {
            /** @var Answer $a */
            Asserts::checkState($a->getValueCallCount() === $times, $a->getMethod() . " called should called $times times!");
        });
    }

    public static function atLeastOnce() {
        return new Verifier(function ($a) {
            /** @var Answer $a */
            Asserts::checkState($a->getValueCallCount() >= 1, $a->getMethod() . " called should called once or more!");
        });
    }
}