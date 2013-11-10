<?php
/**
 * Created by PhpStorm.
 * User: jonny
 * Date: 08/08/13
 * Time: 13:28
 */
use services\UserLocalService;

class UserLocalServiceIntegrationTests extends PHPUnit_Framework_TestCase
{

    protected $localService;

    public function setUp(){
        $this->localService = new UserLocalService();
    }

    public function test_getUserArrayByEmail_email_userArrayObject()
    {
        $email = "test";
        $result = $this->localService->getUser($email);
        $actual = json_encode($result);
        $this->assertNotNull($actual);
    }
}