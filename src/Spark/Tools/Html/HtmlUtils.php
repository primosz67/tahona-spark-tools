<?php
/**
 *
 * 
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

    /**
     * Change this : A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt
     * Into this "A 'quote' is <b>bold</b>"
     *
     * @param string $value
     * @return string
     */
    public static function decode(?string $value) :string {
        return htmlspecialchars_decode($value);
    }
}