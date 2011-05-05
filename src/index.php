<?php
/*!
 * index.php
 * Starter for ajk's MVC framework
 *
 */

# Config
define('SESSION_NAME', '===UNTITLED_SESSION===');

# URL patterns
$urlpatterns = array(
    
    // routes: pattern, controller, (action)
    // pattern can contain ?P<action> which will be the action
    
    array('/^$/',                    'Default'),
    array('/^login$/',               'Account', 'Login'),
    array('/^logout$/',              'Account', 'Logout'),
    array('/^error\/(?P<action>)$/', 'Error')
);


# Debugging
$GLOBALS['debug'] = true;
error_reporting(-1); // report EVERYTHING
ini_set('display_errors', '1');

# Module: Timer for debugging
$startarray = explode(" ", microtime());
$starttime = $startarray[1] + $startarray[0];

# Module: Login
$GLOBALS['logged_in'] = false;
if (!empty($_SESSION['user_id'])) {
    $GLOBALS['logged_in'] = true;
}

# Main
include './core.php';
