<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:50 AM
 */

namespace www\utils\Exceptions;


class InvalidTokenException extends BaseException {
    const errorCode=-301;

    function __construct() {
        parent::__construct("Yeah baby... security is very important and you know it" , self::errorCode);
    }
}