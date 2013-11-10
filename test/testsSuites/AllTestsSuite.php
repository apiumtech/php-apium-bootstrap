<?php
//require_once '../config/TestConfig.php';

require_once 'notIntegration/NotIntegrationTestsSuite.php';
require_once 'integration/IntegrationTestsSuite.php';

class AllTestsSuite extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new AllTestsSuite('All Tests Suite');
        $suite->addTestSuite("NotIntegrationTestsSuite");
        $suite->addTestSuite("IntegrationTestsSuite");
        return $suite;
    }
};
?>
