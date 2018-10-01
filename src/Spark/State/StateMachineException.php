<?php
/**
 * Date: 16.09.18
 * Time: 18:17
 */

namespace Spark\State;

use Exception;

class StateMachineException extends Exception {

    public function __construct($message) {
        parent::__construct($message);
    }
}