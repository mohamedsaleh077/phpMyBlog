<?php

namespace Controllers;
use Controllers\Tfa;
use Core\Controller;
use Core\Database;
use Models\AdminDB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Core\Uploader;

class Admin
{
    private $adminDB;
    private $tfa;
    public function __construct()
    {
        if (!$this->checkLogin()){
            $this->login();
        }
        $this->adminDB = new AdminDB();
        $this->tfa = new Tfa();
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
        $tfa = $_POST['2fa'];
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
            if($user['2fa']){
                if (strlen($tfa) !== 6){
                    $errors["tfa"] = "2FA must be 6 numbers! you enabled it lol.";
                }
                if(!$this->tfa->verifyCode($user['2fa'], $tfa)){
                    $errors["tfa_wrong"] = "2FA verification failed, try again";
                }
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['2fa']) && !isset($_POST['csrf'])) {
            die("Invalid request");
        }
        $errors = [];
        $csrf = $_POST['csrf'];
        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }
        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/security");
            die();
        }
        if(!$this->tfa->verifyCode($_SESSION['2FA'], $_POST['2fa'])){
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['old_pwd']) && !isset($_POST['new_pwd']) && !isset($_POST['csrf'])) {
            die("Invalid request");
        }
        $errors = [];
        $csrf = $_POST['csrf'];
        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }
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

    public function categories()
    {
        Controller::view('categories');
    }

    public function uploads(){
        Controller::view("media");
    }

    private function toWebp($input){
        $manager = new ImageManager(new Driver());
        $image = $manager->read($input);
        return $image->toWebp();
    }

    public function uploadFile(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['csrf'])) {
            die("Invalid request");
        }
        $errors = [];
        $csrf = $_POST['csrf'];
        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }
        if (!isset($_FILES["media"]) || ($_FILES['media']['error'] !== UPLOAD_ERR_OK)){
            $errors["media"] = "There was an error uploading your file.";
        }
        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/uploads");
            die();
        }
        $uploader = new Uploader($_FILES["media"], 10);
        $result = $uploader->upload();
        if (!$result["ok"]){
            $errors = $result["error"];
            $_SESSION['errors'] = $errors;
            header("location: /admin/uploads");
            die();
        }
        $this->toWebp($result["path"].$result["fullfilename"])
            ->save($result["path"].$result["filename"].".webp");
        unlink($result["path"].$result["fullfilename"]);
        header("location: /admin/uploads");
        die();
    }

    public function createBlog(){
        Controller::view("creator");
    }

    public function createPost(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['csrf'])) {
            die("Invalid request");
        }
        $errors = [];

        $title  = $_POST["title"];
        $image = $_POST["image"];
        $slug  = $_POST["slug"];
        $post = $_POST["content"];
        $csrf = $_POST['csrf'];
        $keywords = $_POST["keywords"];
        $seo_title = $_POST["seo_title"];
        $meta_description = $_POST["meta_description"];
        $category = $_POST["category_id"];

        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }

        foreach ($_POST as $key => $value) {
            if (strlen($value) <= 0 || strlen($value) > 255){
                $errors[$key] = $key . " must be between 0 and 255 characters.";
            }
        }

        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/uploads");
            die();
        }

        $dbParams = $_POST;
        unset($dbParams['csrf']);
        $dbParams["author_id"] = $_SESSION["id"];
        $this->adminDB->createPost($dbParams);
        header("location: /");
        die();
    }

}