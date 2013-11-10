<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jonny
 * Date: 30/06/13
 * Time: 16:04
 * To change this template use File | Settings | File Templates.
 */
use services\UserLocalService;

require_once dirname(__FILE__) . "/../../../services/IAuthService.php";
require_once dirname(__FILE__) . "/../../../www/adapters/baseServiceAdapter.php";
require_once dirname(__FILE__) . "/../../../www/adapters/userServiceAdapter.php";
require_once dirname(__FILE__) . "/../../../services/UserLocalService.php";
require_once dirname(__FILE__) . "/../../../model/Data/Base/GenericDAO.php";
require_once dirname(__FILE__) . "/../../../model/Data/UserDAO.php";

class UserLocalServiceTests extends PHPUnit_Framework_TestCase
{
    protected $sut;

    public function setUp(){
        $mock=$this->getMock('\model\Data\UserDAO');

        $mock
            ->expects($this->any())
            ->method("updateUser")
            ->will($this->returnValue("the returned id"));
        $mock
            ->expects($this->any())
            ->method("getAllUsers")
            ->will($this->returnValue("user entities"));
        $mock
            ->expects($this->any())
            ->method("findUserEntityByEmail")
            ->will($this->returnValue("user entity"));
        $mock
            ->expects($this->any())
            ->method("removeUser")
            ->will($this->returnValue(true));

        $this->sut = new UserLocalService($mock);
    }

    public function test_getAllUsers_empty_userEntities()
    {
        $actual = $this->sut->getAllUsers();
        $expected = "user entities";
        $this->assertEquals($expected,$actual);
    }
    public function test_removeUser_email_true()
    {
        $email = "testemail";
        $actual = $this->sut->removeUser($email);
        $this->assertTrue($actual);
    }

}