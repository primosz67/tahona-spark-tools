<?php
/**
 *
 * 
 * Date: 05.07.15
 * Time: 11:06
 */

namespace Spark\Security\Exception;


use Spark\Common\Exception\AbstractException;

class AccessDeniedException extends AbstractException{

    protected function getAlternativeMessage() {
        return "Access denied.";
    }
}