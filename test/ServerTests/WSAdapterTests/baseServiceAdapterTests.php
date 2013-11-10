<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:59 AM
 */
require_once dirname(__FILE__)."/../../../libs/utils/TokenUtils.php";
class BaseServiceAdapterTests extends PHPUnit_Framework_TestCase
{
    public function mockTokenTrue()
    {
        $stubbedTokenUtils = $this->getMock('TokenUtils');
        $stubbedTokenUtils
            ->expects($this->any())
            ->method("isValidTokenIntegrity")
            ->will($this->returnValue(true));
        return $stubbedTokenUtils;
    }
}