<?php
/*!
 * ErrorController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './controllers/BaseController.php';

class ErrorController extends BaseController
{
    // could not create a PHP session
    public function Session()
    {
        // TODO: this
    
        # Move to view
        include './views/ui.php';
        include './views/errors/session.php';
    }
}
