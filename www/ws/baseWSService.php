<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:31 AM
 */
require_once dirname(__FILE__).'/../../libs/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

abstract class BaseWSService {

    public $app;
    public $response;
    public $request;
    public $serviceAdapter;

    public function __construct(){
        $this->app = new \Slim\Slim();
        $this->response = $this->app->response();
        $this->request = $this->app->request();
        $this->serviceAdapter = $this->getServiceAdapter();
        $this->initAdapter();
        $this->app->run();
    }
    abstract function getServiceAdapter();
    abstract function initAdapter();

    public function getBody($serviceAdapter, $method=null, $params=null) {
        return $serviceAdapter->{$method}($params);
    }

    public function parseToken($doubleURLed)
    {
        return urldecode($doubleURLed);
    }

}
