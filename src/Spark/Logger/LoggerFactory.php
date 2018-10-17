<?php


namespace Spark\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spark\Core\Annotation\Component;
use Spark\Utils\Asserts;
use Spark\Utils\Objects;


class LoggerFactory {

    /**
     * @var LoggerFactory
     */
    private static $instance;

    /**
     * @var Logger
     */
    private $logger;

    private function __construct(LoggerFactoryDefinition $loggerFactoryDefinition) {
        $this->logger = new Logger('my_logger');
        $loggerFactoryDefinition->pushHandlers($this->logger);
    }

    public static function init(LoggerFactoryDefinition $def) {
        Asserts::checkState(Objects::isNull(self::$instance));
        self::$instance = new LoggerFactory($def);
    }

    public static function getLogger($class): Logger {
        return self::$instance->createLogger($class);
    }


    public function createLogger($class): Logger {
        return $this->logger->withName($class);
    }
}