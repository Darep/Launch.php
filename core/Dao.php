<?php
/*!
 * DAO base class
 */

class Dao
{
    protected $table = null;
    protected static $dbh = null; // database handle, shared by all the DAOs
    
    function __construct(PDO $pdo = null)
    {
        if (!isset($pdo)) {
            $pdo = self::$dbh;
        }
        
        if (!isset($pdo)) {
            // TODO: print out recovery instructions for users
            throw new DomainException('No PDO object defined for DAO(s)! Critical failure!');
        }
        
        self::$dbh = $pdo;
    }

    static public function setPDO(PDO $pdo)
    {
        self::$dbh = $pdo;
    }
}
