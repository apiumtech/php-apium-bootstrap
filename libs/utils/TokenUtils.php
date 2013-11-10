<?php
use www\utils\Exceptions\InvalidTokenException;

/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:49 AM
 */

class TokenUtils
{

    public function isValidTokenIntegrity($token)
    {
        //TODO: implement some login on the token
        return true;
    }
    public function getEmailFromValidToken($token)
    {
        $email = 'elder.wiggin@academy.edu';
        return $email;
    }
}