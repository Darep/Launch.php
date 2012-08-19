<?php
/*!
 * ErrorController
 */

class ErrorController extends Controller
{
    // could not create a PHP session
    public function Session()
    {
        // TODO: show a view
        echo 'Could not create a PHP session';
    }
}
