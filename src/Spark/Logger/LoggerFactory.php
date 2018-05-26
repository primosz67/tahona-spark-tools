<?php


namespace Spark\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spark\Core\Annotation\Component;


class LoggerFactory {
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(LoggerFactoryDefinition $loggerFactoryDefinition) {
        $this->logger = new Logger('my_logger');

        $loggerFactoryDefinition->pushHandlers($this->logger);
    }

    public function getLogger($class): Logger {
        return $this->logger->withName($class);
    }
}