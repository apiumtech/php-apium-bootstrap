<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 12:29 AM
 */
namespace services;
interface IAuthService
{
    public function getTokenByUserAndPass($user, $password);
}
