<?php

class Model {
    protected $table = null;
    protected static $dbh = null; // database handle, shared by all the DAOs

    function __construct($dbh = null)
    {
        if (!isset($dbh) && !isset(self::$dbh)) {
            // TODO: print out recovery instructions for users
            throw new DomainException('No database configured! Critical failure!');
        }

        if (!empty($dbh)) {
            self::$dbh = $dbh;
        }
    }

    static public function setDBHandle($dbh)
    {
        self::$dbh = $dbh;
    }
}
