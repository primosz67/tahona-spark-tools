<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 28.06.14
 * Time: 02:26
 */

namespace spark\tools\mail;


use spark\common\IllegalArgumentException;
use spark\utils\Asserts;
use spark\utils\Objects;
use spark\utils\ValidatorUtils;

class Mail {
    const D_CONTENT = "content";
    const D_FROM = "from";
    const D_TITLE = "title";

    private $title;
    private $content;
    private $from;
    private $to;
    private $cc;

    /**
     * @param mixed $cc
     */
    public function setCc($cc) {
        $this->cc = $cc;
    }

    /**
     * @return mixed
     */
    public function getCc() {
        return $this->cc;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param $fromEmail
     * @param null $fromName
     * @throws IllegalArgumentException
     */
    public function setFrom($fromEmail, $fromName = null) {
        Asserts::checkArgument(Objects::isString($fromEmail), "fromEmail must be string");
        Asserts::checkArgument(Objects::isString($fromName), "fromName must be string");

        if (Objects::isNull($fromName)) {
            $fromName = $fromEmail;
        }

        $this->from = array($fromEmail => $fromName);
    }

    /**
     * @return mixed
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param String $to
     */
    public function setTo($to) {
        Asserts::checkArgument(Objects::isString($to), "Recipient (to) need to be string");
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getTo() {
        return $this->to;
    }

}