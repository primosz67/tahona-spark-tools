<?php

namespace Spark\Client\Exception;


class ClientException extends \Exception {

    function __construct($message, $e = null) {
        parent::__construct($message, 0, $e);
    }
}