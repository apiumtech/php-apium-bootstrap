<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xavi
 * Date: 7/4/13
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__).'/../../libs/utils/ConfUrlUtils.php';

class ConfUrlUtilsTests extends PHPUnit_Framework_TestCase
{


    public function test_extractInstallDir_emptygetcwd_returnsBaseDir()
    {
        $cwd = "";
        $actual = ConfUrlUtils::extractInstallDir($cwd);
        $expected = "/";
        $this->assertEquals($expected, $actual);

    }


    public static function baseUrls()
    {
        return array(
            array("","/database.prod.conf.php"),
            array("backoffice.yoursite.com","/database.prod.conf.php"),
            array("www.yoursite.com","/database.prod.conf.php"),
            array("http://www.yoursite.com","/database.prod.conf.php"),
            array("http://localhost","/database.dev.conf.php"),
            array("test.yoursite.com","/database.pre.conf.php"),
            array("http://test.yoursite.com","/database.pre.conf.php"),
            array("http://manualtest.localhost","/database.dev.conf.php"),
            array("http://base.localhost","/database.dev.conf.php"),
            array("localhost","/database.dev.conf.php"),
        );
    }


    /**
     * @dataProvider baseUrls
     */
    public function test_getDatabaseConfFile_default_prodFile($base,$expected)
    {
        $actual = ConfUrlUtils::getDatabaseConfFile($base);
        $this->assertEquals($expected, $actual,$base);
    }



    public static function wsUrls()
    {
        return array(
            array("base.localhost/ws/","/database.dev.conf.php"),
        );
    }
    /**
     * @dataProvider wsUrls
     */

    public function test_init_whenBackofficeTest_WS_BASE_is_test_yoursite($expected,$confFile)
    {
        include dirname(__FILE__).'/../../config' . $confFile . '';
        $actual = WS_URL;
        $this->assertEquals($expected, $actual,$confFile);
    }




}