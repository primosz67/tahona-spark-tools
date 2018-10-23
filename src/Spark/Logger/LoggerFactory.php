<?php


namespace Spark\Logger;

use Monolog\Handler\StreamHandler;
use Spark\Core\Annotation\Component;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;


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

    public function getLogger($class): Logger {
        $el = StringUtils::split($class, '\\');

        if (Collections::size($el) > 2) {

            $lastIndex = count($el) - 1;

            $module = $el[0];
            $className = $el[$lastIndex];
            $sublist = Collections::stream(Collections::subList($el, 1, $lastIndex-1))
                ->map(function ($x) {
                    return substr($x, 0, 1);
                })->get();

            $class = $module . "\\" . StringUtils::join("\\", $sublist) . "\\" . $className;
        }

        return $this->createLogger(StringUtils::replace($class, "\\", '.'));
    }


    private function createLogger($class): Logger {
        return new LoggerWrapper($this->logger->withName($class));
    }
}