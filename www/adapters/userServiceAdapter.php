<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:32 AM
 */
namespace www\adapters;
use services\UserLocalService;
require_once dirname(__FILE__) . '/../../libs/utils/TokenUtils.php';

class UserServiceAdapter extends BaseServiceAdapter
{
    function __construct($localService = null, $tokenUtils = null)
    {
        $this->localService = ($localService==null) ? new UserLocalService() : $localService;
        $this->tokenUtils = ($tokenUtils==null) ? new \TokenUtils() : $tokenUtils;
    }

    public function updateUser($token, $user)
    {
        try {
            if ($this->tokenUtils->isValidTokenIntegrity($token))
            {
                $email = $this->tokenUtils->getEmailFromValidToken($token);
                $this->response = $this->localService->updateUser($email, json_decode($user));
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
        return $this->paintResponse();
    }

    public function getUser($token)
    {
        try {
            if ($this->tokenUtils->isValidTokenIntegrity($token))
            {
                $email = $this->tokenUtils->getEmailFromValidToken($token);
                $this->response = $this->localService->getUser($email);
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
        return $this->paintResponse();
    }

    protected function constructOkResponse()
    {
        $response = array('user' => $this->response, 'status' => $this->status);
        return json_encode($response);
    }
}
