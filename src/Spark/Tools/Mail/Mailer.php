<?php
/**
 *
 *
 * Date: 27.06.14
 * Time: 06:49
 */

namespace Spark\Tools\Mail;


use Spark\Config;
use Spark\Core\ConfigAware;
use Spark\Utils\Asserts;
use Spark\Utils\Collections;
use Spark\Utils\StringUtils;


/**
 * use MailMessageSender
 *
 * Class Mailer
 * @package Spark\tools\mail
 */
class Mailer implements ConfigAware {

    public const NAME = 'mailer';

    /**
     * @var MailerConfig
     */
    private $configuration;
    /**
     * @var MailHandler
     */
    private $handler;

    public function __construct(MailerConfig $configuration, MailHandler $handler) {
        $this->configuration = $configuration;
        $this->handler = $handler;
    }


    public function send(Mail $mail) {
        Asserts::checkArgument($this->isValid($mail), 'Wrong email format!');

        $this->handler->send($mail, $this->configuration);
    }

    /**
     * @deprecated
     */
    public function sendMail($mailTO, $userName, $title, $content) {
        $mail = new Mail();
        $mail->setTo($mailTO);
        $mail->setTitle($title);
        $mail->setContent($content);

        $this->send($mail);
    }

    public function setConfig(Config $config) {
        $this->config = $config;
    }

    private function isValid(Mail $mail) {
        return MailUtils::isToValid($mail->getTo())
            || MailUtils::isToValid($mail->getFrom());

    }

}