<?php


namespace Spark\Logger;


class LoggerFactory {


    /**
     * @var Logger
     */
    private $logger;

    private function __construct(LoggerFactoryDefinition $loggerFactoryDefinition) {
        $this->logger = new \Monolog\Logger('my_logger');
        $loggerFactoryDefinition->pushHandlers($this->logger);
    }

    public static function create(LoggerFactoryDefinition $def) {
        return new LoggerFactory($def);
    }


    public function createLogger(): Logger {
        return new LoggerWrapper($this->logger);
    }
}