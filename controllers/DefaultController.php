<?php
/*!
 * DefaultController
 */

class DefaultController extends Controller
{
    public function Index()
    {
        $this->renderView('home.php');
    }

}
