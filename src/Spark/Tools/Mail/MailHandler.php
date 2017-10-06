<?php


namespace Spark\Tools\Mail;


interface MailHandler {
    public function send(Mail $mail, MailerConfig $config);
}