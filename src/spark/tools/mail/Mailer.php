<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 27.06.14
 * Time: 06:49
 */

namespace spark\tools\mail;


use spark\Config;
use spark\core\ConfigAware;
use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\Objects;


/**
 * use MessageSender
 *
 * Class Mailer
 * @package spark\tools\mail
 */
class Mailer implements ConfigAware {

    const NAME = "mailer";

    private $server;
    private $serverPassword;
    private $protocol;
    private $address;

    /**
     * @var Config
     */
    private $config;

    public function send(Mail $mail) {
        $sendMails = $this->config->getProperty("mail.sendMails");

        Asserts::checkState($this->isValid($mail), "Błędny format mailera");

        if ($sendMails === true) {
            $message = \Swift_Message::newInstance();
            $message->setBcc($mail->getCc());
            $message->setSubject($mail->getTitle());

            $message->setFrom($this->getEmail($mail->getFrom()));
            $message->setTo($this->getEmail($mail->getTo()));
            $message->setBody($mail->getContent(), "text/html");

            $mailTransport = \Swift_MailTransport::newInstance();
            $mailTransport->send($message);
        }
    }

    public function sendMail($mailTO, $userName, $title, $content) {
        $mail = new Mail();
        $mail->setTo(array($mailTO => $userName));
        $mail->setTitle($title);
        $mail->setContent($content);

        $this->send($mail);
    }

    public function setConfig(Config $config) {
        $this->config = $config;
    }

    /**
     * @param $email
     * @return array
     */
    private function getEmail($email) {
        if (empty($email)) {
            $title = $this->config->getProperty(Config::MAIL_FROM_TITLE_KEY);
            $email = $this->config->getProperty(Config::MAIL_FROM_EMAIL_KEY);
            return array($email => $title);
        } else {
            if (Objects::isArray($email)) {
                return $email;
            }

            return array($email);
        }
    }

    private function isValid(Mail $mail) {
        return MailUtils::isToValid($mail->getTo())
        || MailUtils::isToValid($mail->getFrom());

    }
}