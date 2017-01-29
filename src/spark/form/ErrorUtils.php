<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 17.08.14
 * Time: 14:57
 */

namespace spark\form;


use spark\utils\Collections;

class ErrorUtils {

    public static function errorsAsValues($errors = array()) {
        $resultErrors = array();
        foreach($errors as $key=>$arrayMessages) {
            Collections::addAll($resultErrors, $arrayMessages);
        }
        return $resultErrors;
    }
}