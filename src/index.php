<?php
/*
 * index.php
 * Starter for ajk's MVC framework
 *
 */

# Security
if (get_magic_quotes_gpc()) die('PHP\'s Magic quotes are on! Disable them.');

define('SITE_BASE', str_replace('index.php', '', $_SERVER['PHP_SELF']));
define('SESSION_NAME', '===UNTITLED_SESSION===');

if (!session_start('SESSION_NAME')) {
    header('Location: '. SITE_BASE .'error/session/');
    exit;
}


error_reporting(-1); // report EVERYTHING

// Temporary timer for development
$startarray = explode(" ", microtime());
$starttime = $startarray[1] + $startarray[0];

$urlpatterns = array(
    // routes: pattern, controller, (action)
    // pattern can contain ?P<action> which will be the action
    array('/^login$/', 'Account', 'Login'),
    array('/^logout$/', 'Account', 'Logout'),
    array('/^$/', 'Frontpage')
);


/* ------------------------------------------------------------------------- */

$url = $_SERVER['REQUEST_URI'];

// trim the url
if (SITE_BASE != '/')
{
    $trans[SITE_BASE] = '';
}
else
{
    $url = substr($url, 1);
}

$trans['?'. $_SERVER['QUERY_STRING']] = '';

$url = strtr($url, $trans);

// trim tailing slash
if (substr($url, -1) == '/') $url = substr($url, 0, -1);

// DEBUG:
//header('Content-type: text/plain'); print $url; exit;

// i18n
$language = 'en';
$langs = array();

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

    if (count($lang_parse[1])) {
        $langs = array_combine($lang_parse[1], $lang_parse[4]);
        foreach ($langs as $lang => $val) {
            if ($val === '') $langs[$lang] = 1;
        }
        arsort($langs, SORT_NUMERIC);
    }
}

foreach ($langs as $lang => $val) {
    if (strpos($lang, 'fi') === 0) {
        //$language = 'fi';
    }
    else if (strpos($lang, 'de') === 0) {
        $language = 'de';
    }
    else if (strpos($lang, 'en') === 0) {
        // show English site
    } 
}


// ENGINE
$controller = null;
$action = 'Index'; // default action
$params = array();

foreach ($urlpatterns as $pattern) {
    if (preg_match($pattern[0], $url, $matches)) {
        $controller = $pattern[1];
        
        foreach ($matches as $key => $value) {
            if (!is_numeric($key)) {
                $params[$key] = $value;
            }
        }

        if (count($pattern) > 2)
        {
            $action = $pattern[2];
        }
        
        if (isset($params['action']))
        {
            $action = $params['action'];
        }

        break;
    }
}


// LOGIN SHENANIGANS
$GLOBALS['logged_in'] = false;
if (!empty($_SESSION['user_id'])) {
    $GLOBALS['logged_in'] = true;
}


// MOVE ON TO CONTROLLER
$access = true;
if ($controller != null)
{
    $controller = $controller .'Controller';
    include './controllers/'. $controller .'.php';
    $c = new $controller($params);
    $a = $action;
    
    // TODO: maybe filter some of the parameters?
    $args = array();
    foreach ($params as $key => $arg)
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
    
    // old way:
    //$c->{$action}();
}
else
{
    include './controllers/BaseController.php';
    $c = new BaseController($params);
    $c->NotFound();
}

exit;
