<?php
require_once dirname(__FILE__) . '/../../ServerTests/LocalServicesTests/LocalServicesIntegrationTests/userLocalServiceIntegrationTests.php';
require_once dirname(__FILE__) . '/../../IntegrationTests/RestSimpleDomainsLibraryTests.php';


class IntegrationTestsSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Integration test suite');

        $suite->addTestSuite("userLocalServiceIntegrationTests");
        $suite->addTestSuite("RestSimpleDomainsLibraryTests");

        return $suite;
    }
}

?>
