<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:36 AM
 */
require_once dirname(__FILE__).'/baseWSService.php';
use www\adapters\UserServiceAdapter;

class UserService extends BaseWSService {

    public function __construct() {
        parent::__construct();
    }

    public function getServiceAdapter(){
        return new UserServiceAdapter();
    }

    function initAdapter()
    {
        $res = $this->response;
        $req = $this->request;
        $serviceAdapter = $this->serviceAdapter;
        $this->app->get('/getUser/:token', function ($token) use($res, $serviceAdapter) {
            $res->header('Content-Type', 'application/json');
            $token = $this->parseToken($token);
            $body = $serviceAdapter->getUser($token);
            $res->body($body);
        });
        $this->app->put('/updateUser/:token', function ($token) use($res, $req, $serviceAdapter) {
            $res->header('Content-Type', 'application/json');
            $token = $this->parseToken($token);
            $user = $req->getBody();
            $body = $serviceAdapter->updateUser($token,$user);
            $res->body($body);
        });
    }
}
$service = new UserService();