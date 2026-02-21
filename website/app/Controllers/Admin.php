<?php

namespace Controllers;
use Core\Controller;
use Models\AdminDB;

class Admin
{
    private $adminDB;
    public function __construct()
    {
        if ($this->checkLogin()){
            $this->dashboard();
        } else {
            $this->login();
        }

    }

    private function checkLogin(){
        if(isset($_SESSION['login']) && $_SESSION['login'] === true) {
            return true;
        }
        return false;
    }

    public function login(){
        if ($this->checkLogin()){
            header("location: /admin/");
            die();
        }
        Controller::view("login");
    }

    public function auth(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['username']) && !isset($_POST['password'])) {
            die("Invalid request");
        }
        $username = $_POST['username'];
        $password = $_POST['password'];
        $csrf = $_POST['csrf'];

        $errors = array();

        if (strlen($username) < 1 || strlen($username) > 255){
            $errors["username_length"] = "Username must be between 2 and 255 characters";
        }

        if (strlen($password) < 7 || strlen($password) > 255){
            $errors["password_length"] = "Password must be between 8 and 255 characters";
        }

        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }

        $this->adminDB = new AdminDB();
        $c = $this->adminDB->checkUsers();
        if (!$c[0]['c']){
            $this->register($username, $password);
        }else{
            $user = $this->adminDB->getUser($username)[0];
            if($user){
                if(!password_verify($password, $user['pwd_hash'])){
                    $errors["wrong_password"] = "Wrong password";
                }
            } else {
                $errors["username"] = "Username doesn't exists";
            }
        }
        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/login");
            die();
        }
        $_SESSION['username'] = $username;
        $_SESSION['login'] = true;
        header("location: /admin/dashboard");
        die();
    }

    private function register($username, $password)
    {
        $options = [
            'cost' => 12
        ];
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT, $options);
        $this->adminDB->createUser($username, $hashedPwd);
    }

    public function dashboard(){
        Controller::view('dashboard');
    }

    public function logout(){
        session_destroy();
        header('Location: /admin/');
        die();
    }
}