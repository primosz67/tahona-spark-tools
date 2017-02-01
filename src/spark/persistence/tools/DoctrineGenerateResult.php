<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.01.15
 * Time: 23:16
 */

namespace spark\persistence\tools;


use spark\tools\html\HtmlUtils;

class DoctrineGenerateResult {

    private $messages = array();

    const MESSAGE = "message";

    const ACTION = "action";

    const FIELD_CLASS = "class";

    public function addMessage($message, $action=" ", $class=" ") {
        $this->messages[] =
            array(
                self::MESSAGE =>"".$message,
                self::ACTION =>"".$action,
                self::FIELD_CLASS =>"".$class,
            );
    }

    public function display() {
        $element = HtmlUtils::builder()
            ->tag("table");

        $element = $element->tag("tr")
            ->tag("td")->tag("b", "State")->end()
            ->tag("td")->tag("b", "Class")->end()
            ->tag("td")->tag("b", "Error message")->end()
            ->end();

        foreach($this->messages as $message) {
            $element = $element->tag("tr")
                ->tag("td")->tag("b", $message[self::ACTION])->end()
                ->tag("td", $message[self::FIELD_CLASS])
                ->tag("td", $message[self::MESSAGE])
                ->end();
        }

        echo $element->end()
            ->get();


        exit;
    }
}