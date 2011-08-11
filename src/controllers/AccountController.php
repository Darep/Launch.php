<?php
/*!
 * AccountController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './core/Controller.php';
include './services/UserService.php';

class AccountController extends Controller
{
    public function LogIn()
    {
    }

	public function DoLogIn()
	{
        $this->httpPostRequired();
		
		
	}
	
    public function LogOut()
    {
    }
}
