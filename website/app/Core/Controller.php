<?php

namespace Core;

class Controller
{
    static public function view($view) {
        require_once  $_SERVER['DOCUMENT_ROOT'] . '/app/Views/' . $view . '.php';
    }
}