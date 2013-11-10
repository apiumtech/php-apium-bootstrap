<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:21 AM
 */

require_once dirname(__FILE__) . "/../TestUtils/RestClientResourcesUtils.php";

class RestSimpleDomainsLibraryTests extends RestClientResourcesUtils
{
    private $productionServerUrl;
    private $developmentServerUrl;

    protected function setUp()
    {
        parent::setUp();
        $this->productionServerUrl = "your_production_site";
        $this->developmentServerUrl = "http://facadesample.localhost";
    }

    public function test_getUser_someGoodResponse()
    {
        $token = "you_can_put_some_security_token_to_check_this";
        $url = "{$this->developmentServerUrl}/ws/userService.php/getUser/".$token;
        $response = $this->executeRestClient($url);
        $expected = '
{"user":{"name":"elder","pass":"raman","email":"elder.wiggin@academy.edu"},"status":"ok"}';
        $this->assertEquals($response,$expected);
    }

    public function test_updateUser_someGoodRequest()
    {
        //TODO: implement PUT method if needed
        $token = "someOtherSecurityToken";
        $url = "{$this->developmentServerUrl}/ws/userService.php/updateUser/".$token;
        $response = $this->executeRestClient($url);
        $expected = '
<html><head><title>404 Page Not Found</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body><h1>404 Page Not Found</h1><p>The page you are looking for could not be found. Check the address bar to ensure your URL is spelled correctly. If all else fails, you can visit our home page at the link below.</p><a href="/ws/userService.php/">Visit the Home Page</a></body></html>';
        $this->assertEquals($response,$expected);
    }
}