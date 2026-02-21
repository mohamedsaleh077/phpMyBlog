<?php

namespace Core;

class App
{
    protected $controllerName = '';
    protected $method = 'index';
    protected $params = [];
    protected $url;
    protected $controller;

    public function __construct()
    {
        // Parse url into readable string
        $this->ParseUrl();

        $this->url[0] = ucfirst(strtolower( $this->url[0] ?? '' ));

        $this->SetController();
        $this->SetMethod();
        $this->SetParams();

        $this->controller = new $this->controllerName();
        // Create a new instance of the controller
        if(!class_exists($this->controllerName) || !is_callable([$this->controller, $this->method])) {
            $this->PageNotFound();
        }

        call_user_func_array([($this->controller), $this->method], $this->params);
    }

    // Parse url  into useable array
    private function ParseUrl()
    {
        if (!isset($_GET['url'])){
            $this->url = [];
            return;
        }
        $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
        $url = preg_replace('/[^a-zA-Z0-9\/]/', '', $url);
        $this->url = explode('/', $url);
    }

    private function SetController()
    {
        if (empty($this->url[0])) {
            $this->controllerName = 'Controllers\\Home';
            return;
        }

        $controller_file = $_SERVER['DOCUMENT_ROOT'] . '/app/Controllers/' . $this->url[0] . '.php';
        if (file_exists($controller_file)) {
            $this->controllerName = 'Controllers\\' . $this->url[0];
            unset($this->url[0]);
            return;
        }

        if ($this->controllerName === '') {
            $this->PageNotFound();
        }

    }

    private function SetMethod()
    {
        if (!isset($this->url[1])) {
            return;
        }

        if (!method_exists($this->controllerName, $this->url[1])) {
            $this->PageNotFound();
            return;
        }

        $this->method = $this->url[1];
        unset($this->url[1]);
    }

    private function SetParams()
    {
        $this->params = isset($this->url[2]) ? array_values($this->url) : [];
    }

    private function PageNotFound()
    {
        http_response_code(404);
        die();
    }

}