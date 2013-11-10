<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:38 AM
 */

namespace www\utils\Exceptions;

abstract class BaseException extends \Exception implements IException{

    public function getSerializedErrorMessage()
    {
        $error = array('error' => $this->getMessage(),'code'  => $this->getCode());
        return $error;
    }
} 