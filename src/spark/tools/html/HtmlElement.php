<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 21.09.16
 * Time: 18:41
 */

namespace spark\tools\html;


use spark\common\IllegalStateException;
use spark\utils\Objects;
use spark\utils\StringUtils;
use tahona\cms\domain\HtmlComponent;

class HtmlElement {

    private $tag;
    /**
     * @var HtmlElement
     */
    private $element;

    /**
     * @var string
     */
    private $content;

    /**
     * HtmlElement constructor.
     * @param $tag
     */
    public function __construct($tag = null, &$element = null) {
        $this->tag = $tag;
        $this->element = $element;
    }


    /**
     * @param $tag
     * @return HtmlElement
     */
    public function tag($tag, $content = null) {
        $htmlElement = new HtmlElement($tag, $this);
        $htmlElement->setContent($content);

        if (Objects::isNotNull($content)) {
            return $htmlElement->end();
        } else {
            return $htmlElement;
        }
    }


    public function end() {
        $content = $this->get();
        if (Objects::isNotNull($this->element)) {
            $this->element->setContent($content);
            return $this->element;
        } else {
            throw new IllegalStateException("Error, you should use get() on Root element");
        }
    }

    private function setContent($content) {
        $this->content = $this->content.$content;
    }

    public function get() {
        return HtmlUtils::tag($this->tag, $this->content, array());
    }
}