<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 21.08.15
 * Time: 23:54
 */

namespace Spark\Tools\Html;


class HtmlUtils {
    public static  function tag($tag, $value="", $attr = array()) {
        $attrText = "";
        foreach ($attr as $k => $v) {
            $attrText .= " $k='$v'";
        }

        return "<$tag $attrText>$value</$tag>";
    }

    /**
     * @return HtmlElement
     */
    public static function builder($rootTag="span") {
        return new HtmlElement($rootTag);
    }
}