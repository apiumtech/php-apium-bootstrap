<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:38 AM
 */

namespace www\utils\Exceptions;

interface IException
{
    public function getSerializedErrorMessage();
}