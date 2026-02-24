<?php

namespace Core;

class Controller
{
    static public function view($view, $data = []) {
        require_once  $_SERVER['DOCUMENT_ROOT'] . '/app/Views/' . $view . '.php';
    }
}