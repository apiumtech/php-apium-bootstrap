<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:43 AM
 */
require_once dirname(__FILE__) . '/../../www/ws/baseWSService.php';

class dummy extends BaseWSService {


    function __construct() {}

    function getServiceAdapter()
    {
        // TODO: Implement getServiceAdapter() method.
    }

    function initAdapter()
    {
        // TODO: Implement initAdapter() method.
    }

    function getServiceURI($method = null)
    {
        // TODO: Implement getServiceURI() method.
    }

    function getBody($serviceAdapter, $method = null, $params = null)
    {
        // TODO: Implement getBody() method.
    }
}

class BaseWSServiceTests extends PHPUnit_Framework_TestCase
{
    public function test_parseToken_doubleEncodedString_encodedString()
    {
        $dummy = new dummy();
        $doubleURLed = "hola%2B%252F%2B%252B%2Bpetardaken";
        $actual = $dummy->parseToken($doubleURLed);
        $expected = "hola+%2F+%2B+petardaken";
        $this->assertEquals($expected, $actual);
    }

}
?>