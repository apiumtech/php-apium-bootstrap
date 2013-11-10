<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jonny
 * Date: 31/07/13
 * Time: 13:15
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__) . '/../../ServerTests/LocalServicesTests/UserLocalServiceTests.php';

class LocalServiceTestsSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('All Tests which are not integration tests Suite');
        $suite->addTestSuite("UserLocalServiceTests");

        return $suite;
    }
}