<?php


namespace Spark\Tools\Mail;


use Spark\Common\Collection\FluentIterables;
use Spark\Utils\Collections;
use Spark\Utils\StringUtils;

class SimpleMailHandler implements MailHandler {

    public function send(Mail $mail, MailerConfig $config) {
        $headers = FluentIterables::of([])
            ->add('MIME-Version: 1.0')
            ->add('Content-type: text/html; charset=utf-8')
            ->add('To: ' . $this->toMailHeader($mail->getTo()))
            ->add('From: ' . $this->toMailHeader($mail->getFrom()))
            ->add('List-Unsubscribe: <' . $mail->getUnsubscribeUrl() . '>')
            ->get();

        mail($mail->getTo(), $mail->getTitle(), $mail->getContent(),
            StringUtils::join("\r\n", $headers));
    }


    private function toMailHeader($mail) {
        if (is_array($mail)) {
            $mailList = array();
            foreach ($mail as $mailValue => $mailText) {
                $mailList[] = $mailText . ' <' . $mailValue . '>,';
            }
            return StringUtils::join(',', $mailList);
        }
        return $mail;
    }
}