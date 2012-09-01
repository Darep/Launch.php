<?php
/*!
 * DefaultController
 */

class MainController extends Controller
{
    public function Index()
    {
        $this->renderView('main/index');
    }
}
