<?php
namespace model\Data;
//require_once dirname(__FILE__).'/../../services/lib/Log4phpLogger.php';
//require_once dirname(__FILE__).'/../../services/lib/Logger.class.inc';

use model\Data\Base\GenericDAO;

interface IUserDAO
{
}
class UserDAO extends GenericDAO implements IUserDAO
{
    public function isValidUsername($user)
    {
        return true;
    }

    public function isValidUser($user, $password)
    {
        return true;
    }

    public function getAllUsers(){
        $user1 = array('name' => 'fake','id'=>'1');
        $user2 = array('name' => 'fake','id'=>'2');
        $users = array($user1,$user2);
        return $users;
    }

    public function findUserById($id){
        $user = array('name' => 'fake','id'=>$id);
        return $user;
    }
}
