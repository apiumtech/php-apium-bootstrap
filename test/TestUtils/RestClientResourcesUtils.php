<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fjhidalgo
 * Date: 6/24/13
 * Time: 5:49 PM
 * To change this template use File | Settings | File Templates.
 */

require_once dirname(__FILE__)."/../../libs/RESTClient/RestClient.class.php";
require_once dirname(__FILE__) . "/../TestUtils/RestClientResourcesUtils.php";

class RestClientResourcesUtils extends PHPUnit_Framework_TestCase
{
    protected $restClient;
    protected $token;

    protected function setUp()
    {
        $this->restClient = new RestClient();
//        $this->token=$this->getTokenFromProd();
    }

    /**
     * @param $url
     * @return mixed
     */
    public function executeRestClient($url)
    {
        $this->restClient->setUrl($url);
        $this->restClient->execute();
        $response = $this->restClient->getResponse();
        return $response;
    }


}