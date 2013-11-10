<?php
require_once dirname(__FILE__) . '/../../ServerTests/ConfUrlUtilsTests.php';

class ConfigurationTestsSuite 
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Configuration Tests Suite');
        //Utils
        $suite->addTestSuite("ConfUrlUtilsTests");
        return $suite; 
    }
}
?>