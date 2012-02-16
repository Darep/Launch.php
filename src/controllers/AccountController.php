<?php
/*!
 * AccountController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './services/ExampleUserService.php';

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
