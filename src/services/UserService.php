<?php
/*!
 * Service for accessing and modifying user data 
 * This is an example implementation of a simple user service, bundled with Launch.php
 *
 * Copyright 2011, Launch.php
 */

require_once('./core/Service.php');


class UserService extends Service
{
    public function GetUser($id)
    {
        try
        {
            if(!self::$dbh) $this->connect();
            
            $result = self::$dbh->query(
                'SELECT *
                 FROM user
                 WHERE id = '. (int)$id .'
                 LIMIT 1'
            );
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }
        
        return $rows[0];
    }
    
    
    public function GetUserWithEmail($email)
    {
        try
        {
            if(!self::$dbh) $this->connect();
            
            $result = self::$dbh->query(
                'SELECT id, email, password FROM user
                 WHERE email = "'. mysql_escape_string($email) .'"
                 LIMIT 1'
            );
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            
            
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }

        return $rows[0];
    }


    public function GetUserWithRememberMe($key)
    {
        try
        {
            if(!self::$dbh) $this->connect();
            
            $result = self::$dbh->prepare(
                'SELECT id, username
                 FROM user
                 WHERE rememberme = :key
                 LIMIT 1'
            );
            
            $params = array(
                ':key' => $key
            );
            
            $ret = $result->execute($params);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }
    }

    
    /**
     * Checks that user with ID $user_id has a password of $password
     *
     * @return boolean Does $password match user's password
     */
    public function CheckPassword($user_id, $password)
    {
        try
        {
            if (!self::$dbh) $this->connect();
            
            $password = self::EncryptPassword($password);
            
            $statement = self::$dbh->prepare(
                'SELECT password
                 FROM user
                 WHERE id = :id'
            );
            
            $params = array(':id' => $user_id);
            $ret = $statement->execute($params);
            
            // TODO: do something with $ret
            
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return ($row['password'] == $password);
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }
    }
    
    
    public function ChangePassword($id, $password)
    {
        try
        {
            if (!self::$dbh) $this->connect();
            
            $password = self::EncryptPassword($password);
            
            $statement = self::$dbh->prepare(
                'UPDATE user SET password = :password
                 WHERE id = :id LIMIT 1'
             );
            
            $params = array(
                ':password' => $password,
                ':id'       => $id
            );
            
            $success = $statement->execute($params);
            return $success;
        }
        catch (PDOException $e)
        {
            $this->fatal_error($e->getMessage());
        }
    }


    public function create($user)
    {
        try
        {
            if(!self::$dbh) $this->connect();
            
            $stmt = self::$dbh->prepare(
                "INSERT INTO user (username, email, password)
                        VALUES (:username, :email, :password)");

            $params = array(
                ':username' => $user['username'],
                ':email'    => $user['email'],
                ':password' => self::EncryptPassword($user['password'])
            );
            
            $affected_rows = $stmt->execute($params);

        }
        catch (PDOException $e)
        {
            if ($e->getCode() == 23000) {
                // email already registered
                return 0;
            }
            
            $this->fatal_error($e->getMessage());
        }

        return $affected_rows;
    }

    
    public function modify($item)
    {
    /*
        try {
            if(!self::$dbh) $this->connect();
            
            $query = self::$dbh->prepare(
                "UPDATE task SET cat=:cat, sdesc=:sdesc, 
                                 ldesc=:ldesc, price=:price 
                WHERE id=:id");
            
            $params = array(
                ':cat'   => $item['cat'],
                ':sdesc' => $item['sdesc'],
                ':ldesc' => $item['ldesc'],
                ':price' => $item['price'],
                ':id'    => $item['id']
            );
            
            $ret = $stmt->execute($params);
        
        } catch (PDOException $e) {
            $this->fatal_error($e->getMessage());
        }
        return $ret;
        */
    }

    
    public function load($id = -1, $limit = null)
    {
        $where = '';

        if ($id != -1) {
            $where = "WHERE ". $id;
        }
        
        try {
            if(!self::$dbh) $this->connect();
            
            $result = self::$dbh->query("SELECT id, password FROM user $where LIMIT 1");
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            
            
        } catch (PDOException $e) {
            $this->fatal_error($e->getMessage());
        }

        // we get one row and only want to return that
        return $rows[0];
    }
    
    
    public static function EncryptPassword($password, $extra = '')
    {
        return md5('SUPERSALT:'. $extra . $password);
    }
}
