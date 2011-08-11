<?php
/*!
 * Service for accessing and modifying user data 
 * This is an example implementation of a simple user service, bundled with Launch.php
 *
 * Copyright 2011, Launch.php
 */

require_once('./core/Service.php');
require_once('./dao/UserDao.php');


class UserService extends Service
{
	private $userDao;

	public __construct()
	{
		parent::__construct();
		
		$this->userDao = new UserDao;
	}
	
    public function getUser($id)
    {
        try
        {
			return $this->userDao->getUser($id);
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }
    }
    
    public function addUser($user)
    {
        try
        {
            $user['password'] = self::EncryptPassword($user['password']);
			$this->userDao->createUser($user);
			
			return true;
        }
        catch (PDOException $e)
        {
            if ($e->getCode() == 23000) {
                // email already registered
                return false;
            }
            
            $this->fatal_error($e->getMessage());
        }
	}
	
	
// protected:
    
    protected static function EncryptPassword($password, $extra = '')
    {
        return md5('SUPERSALT:'. $extra . $password);
    }
}
