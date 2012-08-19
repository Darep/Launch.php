<?php
/*!
 * Launch.PHP controller base class
 */

if (empty($access)) exit;

class Controller
{
    protected $params;
    protected $is_ajax;
    protected $isAjax;
    protected $method;

    function __construct($params)
    {
        $this->params = $params;

        $this->is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
        $this->isAjax = $this->is_ajax;
        
        if ($this->is_ajax) {
            header('Cache-control: no-cache'); // don't cache ajax responses
        }

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

    protected function renderJson($view_model = array())
    {
        echo json($view_model);
    }
}


/* Helpers for controllers:
----------------------------------------------------------------------------- */

function redirect($link)
{
    header('Location: ' . $link);
    exit;
}

function json($array)
{
    header('Content-Type: application/json');
    return json_encode($array);
}
