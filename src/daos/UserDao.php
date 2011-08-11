<?php
/*!
 * User DAO
 * Data accessing and modification of users
 */

// no need to include Dao.php, it is already included by core.php
 
class UserDao extends Dao
{
    protected $table = 'user';
    
    public function getUser($id)
    {
        $stmt = self::$dbh->prepare(
			'SELECT *
			 FROM '. $this->table .'
			 WHERE id = '. (int)$id .'
			 LIMIT 1'
        );
        
        $success = $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row;
    }
	
    public function createUser($user)
    {
		$stmt = self::$dbh->prepare(
			'INSERT INTO '. $this->table .' (username, email, password)
					VALUES (:username, :email, :password)');

		$params = array(
			':username' => $user['username'],
			':email'    => $user['email'],
			':password' => $user['password']
		);
		
		$affected_rows = $stmt->execute($params);

        return $affected_rows;
    }
		
}
