<?php
/*!
 * Launch.PHP core
 * version 0.9.2
 */

include './launchphp/helpers.php';

# Start timer for debugging
if (!empty($GLOBALS['debug'])) {
    $startarray = explode(" ", microtime());
    $starttime = $startarray[1] + $startarray[0];
}

/* ------------------------------------------------------------------------- */
// Security & misc. starting stuff

$config_file = './config/database.ini';

if (!file_exists($config_file)) {
    // TODO: instructions for fixing the problem
    die('Database settings not found! (path: '. $config_file .')');
}

if (get_magic_quotes_gpc()) die('PHP\'s Magic quotes are on! Disable them.');

// Session
if (empty($site_session_name)) {
    $site_session_name = 'Launch.php';
}

if (!session_start($site_session_name)) {
    die('Could not start PHP session');
    exit;
}


/* ------------------------------------------------------------------------- */
// URL parsing

$site_base = str_replace('index.php', '', $_SERVER['PHP_SELF']);
define('SITE_BASE', $site_base);

$url = $_SERVER['REQUEST_URI'];

if ($url[strlen($url) - 1] !== '/') {
    $url = $url . '/';
}

if (SITE_BASE !== '/')
{
    // remove site base from the start of the string
    $url = str_replace($site_base, '', $url);
}
else
{
    // remove / at the start
    $url = substr($url, 1);
}

// echo 'SELF: '. $_SERVER['PHP_SELF'] . PHP_EOL;
// echo 'REQUEST: '. $_SERVER['REQUEST_URI'] . PHP_EOL;
// echo 'base: '. SITE_BASE . PHP_EOL;
// echo 'url: '. $url . PHP_EOL;

// remove query string from URL
if (!empty($_SERVER['QUERY_STRING'])) {
    $url = str_replace("?{$_SERVER['QUERY_STRING']}", '', $url);
}

// trim tailing slash
if (substr($url, -1) == '/')
{
    $url = substr($url, 0, -1);
}

// DEBUG:
//header('Content-type: text/plain'); print $url; exit;

/* ------------------------------------------------------------------------- */
// Data-access

$dbh = null;
$db_cfg = parse_ini_file($config_file);

switch ($db_cfg['DB_TYPE']) {
    case 'mysql':
        // TODO: check that PDO has mysql driver
        $dsn = 'mysql:dbname='. $db_cfg['DB_NAME'] .';host='. $db_cfg['DB_HOST'];

        $dbh = new PDO($dsn, $db_cfg['DB_USER'], $db_cfg['DB_PASS'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break;

    case 'sqlite':
        $dsn = 'sqlite:'. $db_cfg['DB_FILE'];

        $dbh = new PDO($dsn);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        break;

    case 'mysqli':
        $dbh = mysqli_connect($db_cfg['DB_HOST'], $db_cfg['DB_USER'], $db_cfg['DB_PASS'], $db_cfg['DB_NAME']);
        break;

    case 'mysql_old':
        $dbh = mysql_connect($db_cfg['DB_HOST'], $db_cfg['DB_USER'], $db_cfg['DB_PASS']);
        mysql_select_db($db_cfg['DB_NAME'], $dbh);
        mysql_query("SET NAMES 'UTF8'");
        break;

    default:
        die('DB_TYPE is invalid in database configuration, check database.ini');
        break;
}

require_once './launchphp/Model.php';
Model::setDBHandle($dbh);


/* ------------------------------------------------------------------------- */
// Route engine

$route = array(
    'method' => null,
    'controller' => null,
    'action' => 'index', // default action
    'params' => array()
);

foreach ($routes as $pattern)
{
    if (preg_match($pattern[1], $url, $matches))
    {

        // Check that the HTTP request's method is valid
        $route['method'] = $method = $pattern[0];

        if ($method !== null && $_SERVER['REQUEST_METHOD'] !== $method) {
            // TODO: validate HTTP method
        }

        // Controller
        $route['controller'] = $pattern[2];

        // Collect all the (?P<x>)'s from the pattern
        foreach ($matches as $key => $value)
        {
            if (!is_numeric($key))
            {
                $route['params'][$key] = $value;
            }
        }

        // Controller action
        // If an action is explicitly set, use that
        if (count($pattern) > 3)
        {
            $route['action'] = $pattern[3];
        }
        else if (isset($route['params']['action']))
        {
            // Otherwise check if the pattern had ?P<action>
            $route['action'] = $route['params']['action'];
        }

        break;
    }
}

// include service base class
require_once './launchphp/Service.php';


/* ------------------------------------------------------------------------- */
// Include modules
//include './modules/mysql_magic.php';


/* ------------------------------------------------------------------------- */
// Surrender control to a controller

$access = true;
include './launchphp/Controller.php';

if ($route['controller'] != null)
{
    $controller = $route['controller'] .'Controller';
    include './controllers/'. $controller .'.php';
    $c = new $controller($route['params']);
    $a = $route['action'];

    // TODO: maybe filter some of the parameters?
    $args = array();
    foreach ($route['params'] as $key => $arg)
    {
        if ($key == 'action')
        {
            continue;
        }
        $args[] = $arg;
    }

    // this method is twice as fast as calling only "call_user_func_array()"
    switch(count($args)) {
        case 0: $c->{$a}(); break;
        case 1: $c->{$a}($args[0]); break;
        case 2: $c->{$a}($args[0], $args[1]); break;
        case 3: $c->{$a}($args[0], $args[1], $args[2]); break;
        case 4: $c->{$a}($args[0], $args[1], $args[2], $args[3]); break;
        case 5: $c->{$a}($args[0], $args[1], $args[2], $args[3], $args[4]); break;
        default: call_user_func_array(array($c, $a), $args);  break;
    }
}
else
{
    $c = new Controller($route['params']);
    $c->notFound();
}

exit;
