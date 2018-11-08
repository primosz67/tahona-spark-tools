<?php
/**
 *
 * 
 * Date: 23.07.16
 * Time: 12:08
 */

namespace Spark\Tools\Mail;


use Spark\Utils\ValidatorUtils;

class MailUtils {

    public static function isToValid($to) {
        if (false === is_array($to)) {
            return ValidatorUtils::isMailValid($to);
        }

        if (is_array($to)) {
            foreach ($to as $mail => $name) {
                if (false === ValidatorUtils::isMailValid($mail)) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }
}