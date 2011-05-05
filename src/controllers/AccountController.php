<?php
/*!
 * AccountController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './controllers/BaseController.php';
include './services/UserService.php';

class AccountController extends BaseController
{
    public function LogIn()
    {
        // TODO: log in
    
        # Move to view
        include './views/ui.php';
        include './views/login.php';
    }

    // POST:
    public function LogOut()
    {
        $this->httpPostRequired();
    
        // TODO: log out
    }
}
