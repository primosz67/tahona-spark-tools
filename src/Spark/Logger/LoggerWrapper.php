<?php
/**
 * Date: 18.10.18
 * Time: 05:29
 */

namespace Spark\Logger;


use Spark\Utils\Collections;
use Spark\Utils\Objects;
use Spark\Utils\StringUtils;

class LoggerWrapper implements Logger {


    /**
     * @var \Monolog\Logger
     */
    private $logger;

    private $cache = [];

    public function __construct(\Monolog\Logger $logger) {
        $this->logger = $logger;
    }

    public function debug($obj, string $message): void {
        $this->getLogger($obj)->debug($message);
    }

    public function info($obj, string $message): void {
        $this->getLogger($obj)->info($message);
    }

    public function warn($obj, string $message): void {
        $this->getLogger($obj)->warn($message);
    }

    public function error($obj, string $message): void {
        $this->getLogger($obj)->error($message);
    }

    private function getLogger($obj) {
        return $this->logger->withName($this->getName($obj));
    }


    private function getName($obj) {

        if (Objects::isString($obj)) {
            $class = $obj;
        } else {
            $class = Objects::getSimpleClassName($obj);
        }

        if (!Collections::hasKey($this->cache, $class)) {

            $el = StringUtils::split($class, '\\');

            if (Collections::size($el) > 2) {

                $lastIndex = count($el) - 1;

                $module = $el[0];
                $className = $el[$lastIndex];
                $sublist = Collections::stream(Collections::subList($el, 1, $lastIndex - 1))
                    ->map(function ($x) {
                        return substr($x, 0, 1);
                    })->get();

                $newClass = $module . "\\" . StringUtils::join("\\", $sublist) . "\\" . $className;
                $this->cache[$class] = StringUtils::replace($newClass, "\\", '.');
            }
        }

        return $this->cache[$class];
    }
}