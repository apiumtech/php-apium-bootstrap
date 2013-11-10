<?php
require_once dirname(__FILE__) . '/../../ServerTests/BaseWSServiceTests.php';
require_once dirname(__FILE__) . '/ConfigurationTestsSuite.php';


class UnitTestsSuite
{
    public static function suite()
    {
        $suite=new PHPUnit_Framework_TestSuite('All Unit Tests Suite');

        $suite->addTestSuite("BaseWSServiceTests");
        $suite->addTestSuite("ConfigurationTestsSuite");

        return $suite;
    }
}

?>