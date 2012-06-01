<?php
/*!
 * Service base class
 * Mainly just contains helpers
 */


class Service
{
    function __construct()
    {
    }

    /**
     * Print out an error message and debug information
	 *
	 * @param string $msg Message to be printed
     */
    protected function fatal_error($msg)
    {
        // TODO: if !debug, give user server error 500 and save the information below to a log file
        echo "<pre>Error!: $msg\n";
        
        $bt = debug_backtrace();
        foreach($bt as $line) {
            $args = var_export($line['args'], true);
            echo "{$line['function']}($args) at {$line['file']}:{$line['line']}\n";
        }
        echo "</pre>";
        
        die();
    }

    /**
     * Create a SQL "UPDATE ..." string based on a bunch of arrays.
     * 
     * @param array $data Data to be updated as (key = column => value = data)
     * @param string $updates SQL UPDATE string
     * @param array $params PDO parameters
     */
    protected function sqlUpdateString($data, &$updates, &$params)
    {
        foreach ($data as $key => $value)
        {
            if (!empty($updates))
            {
                $updates .= ', ';
            }
            $updates .= "$key = :$key";
            $params[":$key"] = $value;
        }
    }
}


// Exceptions:
class NotImplemented extends Exception {}


// Useful service functions:

// load_list takes a text file and turns it into a global array cached by APC
function load_list($name)
{
    global $$name;
    if(!$$name = apc_fetch($name)) {
        $$name = explode("\n",trim(file_get_contents($name.'.txt')));
        apc_store($name,$$name);
    }
}


function log_put($string)
{
    $file = 'dblog.txt';
    $contents = file_get_contents($file);
    file_put_contents($file, $contents . $string . "\n");
}


function dump_array($arr)
{
    header('Content-type: text/plain');
    var_dump($arr);
    exit;
}
