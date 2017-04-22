<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 18.04.17
 * Time: 07:18
 */

namespace spark\form\validator;


use spark\form\Validator;
use spark\utils\StringUtils;

class MaxLengthValidator extends Validator {
    private $maxValue;

    /**
     * MaxLengthValidator constructor.
     */
    public function __construct($errorMessage, $maxValue) {
        parent::__construct($errorMessage);
        $this->maxValue = $maxValue;
    }

    public function isValid($value) {
        return StringUtils::length($value) <= $this->maxValue;
    }
}