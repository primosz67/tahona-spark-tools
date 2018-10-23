<?php
/**
 * Date: 18.10.18
 * Time: 05:29
 */

namespace Spark\Logger;


interface Logger {

    public function debug(string $message): void;

    public function info(string $message): void;

    public function warn(string $message): void;

    public function error(string $message): void;

}