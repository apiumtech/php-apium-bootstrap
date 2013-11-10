<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jonny
 * Date: 31/07/13
 * Time: 13:18
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__) . '/../../ServerTests/WSAdapterTests/userServiceAdapterTests.php';

class WSAdapterTestsSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('WSAdapter test suite');

        $suite->addTestSuite("UserServiceAdapterTests");
        return $suite;
    }
}
