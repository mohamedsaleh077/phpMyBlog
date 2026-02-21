<?php

namespace Controllers;
use Core\Controller;
class Home
{
    public function index(){
        Controller::view('home');
    }

}