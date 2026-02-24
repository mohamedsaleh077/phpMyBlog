<?php

namespace Controllers;
use Core\Controller;
use Models\CategoriesDB;

class Categories
{
    private $db;
    public function __construct(){
        $this->db = new CategoriesDB();
    }
    public function listAll(){
        header("content-type: application/json");
        echo json_encode($this->db->getAll()[0]);
        die();
    }

    public function list()
    {
        return $this->db->getAll();
    }

    public function add(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['name']) && !isset($_POST['info']) && !isset($_POST['csrf'])) {
            die("Invalid request");
        }
        $name = $_POST['name'];
        $info = $_POST['info'];
        $csrf = $_POST['csrf'];
        $errors = [];
        if (strlen($name) < 1 || strlen($name) > 255){
            $errors["name_length"] = "name must be between 2 and 255 characters";
        }
        if (strlen($info) > 1000){
            $errors["info_length"] = "Username must be between 2 and 255 characters";
        }
        if ($csrf !== $_SESSION['CSRF']) {
            $errors["csrf"] = "CSRF validation failed";
        }

        if (count($errors) !== 0) {
            $_SESSION['errors'] = $errors;
            header("location: /admin/categories");
            die();
        }

        $this->db->add($name, $info);
        header("location: /admin/categories");
        die();
    }

    public function del($id){
        $this->db->del($id);
        header("location: /admin/categories");
        die();
    }

    public function explore($id){
        Controller::view("category", [$id]);
    }

}