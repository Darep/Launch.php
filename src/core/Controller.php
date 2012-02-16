<?php
/*!
 * BaseController
 *
 * Copyright (c) 2010-2011 Antti-Jussi Kovalainen
 */

if (empty($access)) exit;

class Controller
{
    protected $params;
    protected $is_ajax;
    protected $method;

    function __construct($params)
    {
        $this->params = $params;
        $this->is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
        if ($this->is_ajax) header('Cache-control: no-cache'); // don't cache ajax responses

        $this->method = $_SERVER['REQUEST_METHOD'];
    }


// common pages:

	
    public function notFound()
    {
		$this->renderView('404.php');
        exit;
    }

    
    public function noAuth()
    {
		$this->renderView('no-auth.php');
        exit;
    }


// protected:


    protected function httpPostRequired()
    {
        if ($this->method != 'POST') {
            $this->notFound();
        }
    }

    protected function renderView($view_file, $view_model = null)
    {
        if (!empty($view_model)) {
            extract($view_model);
        }
        
        include './views/ui.php';
        include './views/'. $view_file;
    }
}


/* Useful helpers for controllers :
----------------------------------------------------------------------------- */

function redirect($link)
{
    header('Location: ' . $link);
    exit;
}

function json($success, $message = '', $html = '')
{
    return json_encode(
        array(
            'Success' => $success,
            'Message' => $message,
            'Html' => $html
        )
    );
}

function json2($success, $array)
{
    header('Content-type: application/json');
    $array = array_merge(array('Success' => $success), $array);
    return json_encode($array);
}
