<?php
/**
 * Date: 18.10.18
 * Time: 05:29
 */

namespace Spark\Logger;


interface Logger {

    public function debug($classOrObject, string $message): void;

    public function info($classOrObject, string $message): void;

    public function warn($classOrObject, string $message): void;

    public function error($classOrObject, string $message): void;

}