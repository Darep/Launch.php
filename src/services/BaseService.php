<?php
/**
 * @file
 * Abstract(ish) base class for services.
 *
 * Handles the connection and error reporting.
 */


$config_file = './config.php';

if (!file_exists($config_file))
{
    die('No \'config.php\' file found');
}

require_once($config_file);


class BaseService
{
    protected static $dbh = false;

    
    function __construct()
    {
        $this->connect();
    }


    /**
     *
     * @note No need to error check here - the child classes should enclose call to connect with try-catch
     */
    protected function connect()
    {
        switch (DB_TYPE) {
            case 'mysql':
                $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST;

                self::$dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                break;
            
            case 'sqlite':
            default:
                $dsn = 'sqlite:'. DB_FILE;
                
                self::$dbh = new PDO(self::dsn);
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);              
                break;
        }
    }


    /**
     * 
     */
    protected function fatal_error($msg)
    {
        // FIXME: give user server error 500 and save the information below to a log file
        echo "<pre>Error!: $msg\n";
        
        $bt = debug_backtrace();
        foreach($bt as $line) {
            $args = var_export($line['args'], true);
            echo "{$line['function']}($args) at {$line['file']}:{$line['line']}\n";
        }
        echo "</pre>";
        
        die();
    }
    
    
}


/* Exceptions
----------------------------------------------------------------------------- */

class NotImplemented extends Exception {}


/*
class MysqlStringEscaper
{
    function __get($value)
    {
        return mysql_real_escape_string($value);
    }
}
$mstr = new MysqlStringEscaper;
*/


/* Useful functions
----------------------------------------------------------------------------- */

// load_list takes a text file and turns it into a global array cached by APC
function load_list($name) {
    global $$name;
    if(!$$name = apc_fetch($name)) {
        $$name = explode("\n",trim(file_get_contents($name.'.txt')));
        apc_store($name,$$name);
    }
}


function log_put($string) {
    $file = '/home/ajk/dblog.txt';
    $contents = file_get_contents($file);
    file_put_contents($file, $contents . $string . "\n");
}


function dump_array($arr)
{
    header('Content-type: text/plain');
    var_dump($arr);
    exit;
}
