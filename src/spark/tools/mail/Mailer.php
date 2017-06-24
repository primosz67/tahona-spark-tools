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
use spark\tools\mail\annotation\handler\EnableMailerAnnotationHandler;
use spark\utils\Asserts;
use spark\utils\BooleanUtils;
use spark\utils\Collections;
use spark\utils\Objects;
use spark\utils\StringUtils;


/**
 * use MailMessageSender
 *
 * Class Mailer
 * @package spark\tools\mail
 */
class Mailer implements ConfigAware {

    const NAME = "mailer";

    /**
     * @var Config
     */
    private $config;

    public function send(Mail $mail) {
        $sendMails = $this->config->getProperty(EnableMailerAnnotationHandler::SPARK_MAILER_ENABLED);

        Asserts::checkArgument($this->isValid($mail), "Wrong email format");

        if (BooleanUtils::isTrue($sendMails)) {
//            $message = new \Swift_Message();
//            $message->setBcc($mail->getCc());
//            $message->setSubject($mail->getTitle());
//
//            $message->setFrom($this->getEmail($mail->getFrom()));
//            $message->setTo($this->getEmail($mail->getTo()));
//            $message->setBody($mail->getContent(), "text/html");

//            $mailTransport = new \Swift_SmtpTransport(null);
//            $mailTransport->send($message);

            $headers = Collections::builder()
                ->add('MIME-Version: 1.0')
                ->add('Content-type: text/html; charset=utf-8')
                ->add("To: " . $this->toMailHeader($mail->getTo()))
                ->add("From: " . $this->toMailHeader($mail->getFrom()))
                ->get();

            mail($mail->getTo(), $mail->getTitle(), $mail->getContent(),
                StringUtils::join("\r\n", $headers));
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
        } else if (Objects::isArray($email)) {
            return $email;
        }

        return array($email);
    }

    private function isValid(Mail $mail) {
        return MailUtils::isToValid($mail->getTo())
        || MailUtils::isToValid($mail->getFrom());

    }

    private function toMailHeader($mail) {
        if (is_array($mail)) {
            return $mail[1] . " <" . $mail[0] . ">";
        }
        return $mail;
    }
}