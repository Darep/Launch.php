<?php
/**
 * DefaultController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

include './controllers/BaseController.php';

class DefaultController extends BaseController
{
    public function Index()
    {
    
        # Move to view
        include './views/ui.php';
        include './views/frontpage.php';
    }

}
