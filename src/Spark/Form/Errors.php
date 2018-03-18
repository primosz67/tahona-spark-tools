<?php
/**
 * Created by PhpStorm.
 * User: crownclown67
 * Date: 2018-03-16
 * Time: 07:52
 */

namespace Spark\Form;

interface Errors {

    public function getErrors();

    public function hasErrors() :bool ;
}