<?php
require_once 'UnitTestsSuite.php';
require_once 'LocalServiceTestsSuite.php';
require_once 'WSAdapterTestsSuite.php';


class NotIntegrationTestsSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('All Tests which are not integration tests Suite');
        $suite->addTestSuite("UnitTestsSuite");
        $suite->addTestSuite("LocalServiceTestsSuite");
        $suite->addTestSuite("WSAdapterTestsSuite");
        return $suite;
    }
}
?>
