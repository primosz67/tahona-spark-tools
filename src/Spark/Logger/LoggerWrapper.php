<?php
/**
 * Date: 18.10.18
 * Time: 05:29
 */

namespace Spark\Logger;


class LoggerWrapper implements Logger {


    /**
     * @var \Monolog\Logger
     */
    private $logger;

    public function __construct(\Monolog\Logger $logger) {
        $this->logger = $logger;
    }

    public function debug(string $message): void {
        $this->logger->debug($message);
    }

    public function info(string $message): void {
        $this->logger->info($message);
    }

    public function warn(string $message): void {
        $this->logger->warn($message);
    }

    public function error(string $message): void {
        $this->logger->error($message);
    }
}