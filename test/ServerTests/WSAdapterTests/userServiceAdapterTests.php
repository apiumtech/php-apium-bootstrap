<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:52 AM
 */


use www\adapters\UserServiceAdapter;
require_once dirname(__FILE__) ."/../../../model/Data/Base/GenericDAO.php";
require_once dirname(__FILE__) ."/../../../model/Data/UserDAO.php";
require_once dirname(__FILE__) ."/baseServiceAdapterTests.php";
require_once dirname(__FILE__) ."/../../../services/IAuthService.php";
require_once dirname(__FILE__) ."/../../../www/adapters/baseServiceAdapter.php";
require_once dirname(__FILE__) ."/../../../www/adapters/userServiceAdapter.php";
require_once dirname(__FILE__) ."/../../../services/UserLocalService.php";


class UserServiceAdapterTests extends BaseServiceAdapterTests
{
    private $fixture;

    public function test_updateUser_validToken_JSONUser_statusOK()
    {
        $stubbedLocalService=$this->getMock('services\UserLocalService');
        $stubbedLocalService
            ->expects($this->any())
            ->method("updateUser")
            ->will($this->returnValue("a valid id"));

        $stubbedTokenUtils = $this->mockTokenTrue();
        $this->fixture = new UserServiceAdapter($stubbedLocalService, $stubbedTokenUtils);

        $actual = $this->fixture->updateUser("token", "pepguardiola");
        $expected = '{"user":"a valid id","status":"ok"}';
        $this->assertEquals($expected, $actual);
    }
    public function test_getUser_validToken_statusOK()
    {
        $stubbedLocalService=$this->getMock('services\UserLocalService');
        $stubbedLocalService
            ->expects($this->any())
            ->method("getUser")
            ->will($this->returnValue("a valid entity"));

        $stubbedTokenUtils = $this->mockTokenTrue();
        $this->fixture = new UserServiceAdapter($stubbedLocalService, $stubbedTokenUtils);

        $actual = $this->fixture->getUser("token");
        $expected = '{"user":"a valid entity","status":"ok"}';
        $this->assertEquals($expected, $actual);
    }

}