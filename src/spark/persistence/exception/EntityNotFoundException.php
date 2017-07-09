<?php


namespace spark\persistence\exception;


use spark\common\exception\AbstractException;

class EntityNotFoundException extends AbstractException{

    protected function getAlternativeMessage() {
        return "Entity not found!";
    }

    public static function notFound() {
        return new EntityNotFoundException();
    }
}