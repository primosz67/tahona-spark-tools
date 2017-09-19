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

    const NAME = "mailer";

    public function send(Mail $mail) {

        Asserts::checkArgument($this->isValid($mail), "Wrong email format");

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

    private function isValid(Mail $mail) {
        return MailUtils::isToValid($mail->getTo())
        || MailUtils::isToValid($mail->getFrom());

    }

    private function toMailHeader($mail) {
        if (is_array($mail)) {
            $mailList = array();
            foreach ($mail as $mailValue => $mailText) {
                $mailList[] = $mailText . " <" . $mailValue . ">,";
            }
            return StringUtils::join(",", $mailList);
        }
        return $mail;
    }
}