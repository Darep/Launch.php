<?php
/**
 * login.php
 *
 */


function loginRequired($next = null, $target = 'login/')
{
    $logged_in = !empty($_SESSION['user_id']);
    
    if ($logged_in)
    {
        return true;
    }
    
    $success = tryRememberMeLogin($next);
    if ($success)
    {
        return true;
    }

    if (!empty($target))
    {
        if (empty($next))
        {
            $next = $_SERVER['REQUEST_URI'];
        }
    
        $location = SITE_BASE . $target;
        if (!empty($next) && is_string($next))
        {
            $location .= '?next='. $next;
        }
        
        header('Location: '. $location);
        exit;
    }
    
    return false;
}


function logIn($id, $next = null, $redirect = true)
{
    require_once('./services/UserService.php');
    
    $userService = new UserService();
    $user = $userService->GetUser($id);

    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $user['username'];
    $_SESSION['show_completed'] = true;
    $_SESSION['show_cancelled'] = true;
    session_write_close();

    if (!$redirect) return;
    
    # redirect
    if (!empty($next))
    {
        header('Location: '. $next);
        exit;
    }
    
    header('Location: '. SITE_BASE);
    exit;
}


function tryRememberMeLogin()
{
    if (empty($_COOKIE['rememberme']))
    {
        return false;
    }
    $cookie = $_COOKIE['rememberme'];
    
    require_once('./services/UserService.php');
    $userService = new UserService();
    
    $user = $userService->GetUserWithRememberMe($cookie);
    
    if (!$user)
    {
        return false;
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['show_completed'] = true;
    $_SESSION['show_cancelled'] = true;
    session_write_close();

    return true;
}

