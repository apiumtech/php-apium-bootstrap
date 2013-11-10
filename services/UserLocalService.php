<?php
/**
 * Created by PhpStorm.
 * User: xavi
 * Date: 11/7/13
 * Time: 11:39 AM
 */
namespace services;
use model\Data\UserDAO,
    model\Data\Base\GenericDAO;

class UserLocalService
{
    public $userDAO;

    function __construct($userDAO = null)
    {
        $this->userDAO = ($userDAO==null) ? new UserDAO() : $userDAO;
    }


    public function updateUser($email, $dUser){
        $user = "somevalue";
        $result = $this->userDAO->updateUser($email, $user);
        return $result;
    }

    public function getAllUsers(){
        $result = $this->userDAO->getAllUsers();
        return $result;
    }

    public function removeUser($email){
        return true;
    }

    public function getUser($email){
        $user = array('name'=>'elder','pass'=>'raman','email'=>$email);
        return $user;
    }

    public function getAllUsersSerialized(){
        return GenericDAO::serializeObject($this->getAllUsers());
    }

}