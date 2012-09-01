<?php

class Model {
    protected $table = null;
    protected static $dbh = null; // database handle, shared by all the DAOs

    function __construct(PDO $pdo = null)
    {
        if (!isset($pdo) && !isset(self::$dbh)) {
            // TODO: print out recovery instructions for users
            throw new DomainException('No database configured! Critical failure!');
        }

        self::$dbh = $pdo;
    }

    static public function setPDO(PDO $pdo)
    {
        self::$dbh = $pdo;
    }
}
