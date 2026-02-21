<?php

namespace Controllers;
use Core\Controller;
use Core\Database;
use Models\AdminDB;
use Controllers\Tfa;

class Admin
{
    private $adminDB;
    public function __construct()
    {
        if (!$this->checkLogin()){
            $this->login();
        }
        $this->adminDB = new AdminDB();
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
        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/login");
            die();
        }
        $c = $this->adminDB->checkUsers();
        if ($c[0]['c'] === 0){
            $this->register($username, $password);
            $user = ['id' => Database::lastInsertId(), "2fa" => null];
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
        $_SESSION['id'] = $user['id'];
        $_SESSION['2fa_enable'] = ($user['2fa'] !== null);
        header("location: /admin/");
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

    public function index(){
        Controller::view('dashboard');
    }

    public function logout(){
        session_destroy();
        header('Location: /admin/');
        die();
    }

    public function security(){
        Controller::view('security');
    }

    public function tfa(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['2fa'])) {
            die("Invalid request");
        }
        $tfa = new Tfa();
        if(!$tfa->verifyCode($_SESSION['2FA'], $_POST['2fa'])){
            $_SESSION['errors'] = ["2FA verification failed, try again"];
            header("location: /admin/security");
            die();
        }
        $this->adminDB->addTFA();
        unset($_SESSION['2FA']);
        $_SESSION['2fa_enable'] = true;
        header("location: /admin/security");
        $_SESSION['errors'] = ["2FA verification done, code is saved!"];
        die();
    }

    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['old_pwd']) && !isset($_POST['new_pwd'])) {
            die("Invalid request");
        }
        $errors = [];
        $user = $this->adminDB->getUserByID($_SESSION["id"])[0];
        if(!password_verify($_POST['old_pwd'], $user['pwd_hash'])){
            $errors["wrong_password"] = "Wrong old password";
        }
        $password = $_POST['new_pwd'];
        if (strlen($password) < 7 || strlen($password) > 255){
            $errors["password_length"] = "New Password must be between 8 and 255 characters";
        }
        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/security");
            die();
        }
        $options = [
            'cost' => 12
        ];
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT, $options);
        $this->adminDB->changePwd($hashedPwd);
        $_SESSION['errors'] = ["new password is saved!"];
        header("location: /admin/security");
    }

}