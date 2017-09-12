<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.01.15
 * Time: 23:16
 */

namespace Spark\Persistence\tools;


use Spark\Tools\Html\HtmlUtils;

class DoctrineGenerateResult {

    private $messages = array();

    const MESSAGE = "message";

    const ACTION = "action";

    const FIELD_CLASS = "class";

    public function addMessage($message, $action = " ", $class = " ") {
        $this->messages[] =
            array(
                self::MESSAGE => "" . $message,
                self::ACTION => "" . $action,
                self::FIELD_CLASS => "" . $class,
            );
    }

    public function display() {
        echo $this->getAsString();
        exit;
    }

    public function getAsString() {
        return $this->getContent();
    }

    /**
     * @return string
     * @throws \Spark\Common\IllegalStateException
     */
    private function getContent() {
        $element = HtmlUtils::builder()
            ->tag("table");

        $element = $element->tag("tr")
            ->tag("td")->tag("b", "State")->end()
            ->tag("td")->tag("b", "Class")->end()
            ->tag("td")->tag("b", "Error message")->end()
            ->end();

        foreach ($this->messages as $message) {
            $element = $element->tag("tr")
                ->tag("td")->tag("b", $message[self::ACTION])->end()
                ->tag("td", $message[self::FIELD_CLASS])
                ->tag("td", $message[self::MESSAGE])
                ->end();
        }

        $str = $element->end()->get();
        return $str;
    }
}