<?php
/*!
 * DefaultController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './core/Controller.php';

class DefaultController extends Controller
{
    public function Index()
    {
    
        $this->renderView('frontpage.php');
    }

}
