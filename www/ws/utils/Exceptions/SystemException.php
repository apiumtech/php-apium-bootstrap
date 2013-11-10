<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:38 AM
 */

namespace www\utils\Exceptions;


class SystemException extends BaseException{
    const errorCode=-301;

    function __construct($message, $someInitData = null ) {
        parent::__construct($message , self::errorCode);
    }
} 