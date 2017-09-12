<?php


namespace Spark\Persistence\exception;


use Spark\Common\Exception\AbstractException;

class EntityNotFoundException extends AbstractException{

    protected function getAlternativeMessage() {
        return "Entity not found!";
    }

    public static function notFound() {
        return new EntityNotFoundException();
    }
}