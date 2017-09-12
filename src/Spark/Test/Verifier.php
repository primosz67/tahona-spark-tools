<?php


namespace Spark\Test;


class Verifier {
    /**
     * @var \Closure
     */
    private $func;


    /**
     * Verifier constructor.
     */
    public function __construct(\Closure $func) {
        $this->func = $func;
    }

    public function verify(Answer $a) {
        $func = $this->func;
        $func($a);
    }
}