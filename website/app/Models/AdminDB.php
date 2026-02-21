<?php

namespace Models;
use Core\QueryBuilder;

class AdminDB
{
    private $db;
    public function __construct(){
        $this->db = new QueryBuilder();
    }

    public function checkUsers(){
        return $this->db->count("admins")->Build()->execute();
    }

    public function createUser($username, $password){
        return $this->db->insert("admins", ["username", "pwd_hash"])
            ->Build()->execute([$username, $password]);
    }

    public function getUser($username){
        return $this->db->select("admins", ["*"])
            ->where([["username", "="]])
            ->Build()->execute([$username]);
    }

    public function getUserByID($id){
        return $this->db->select("admins", ["*"])
            ->where([["id", "="]])
            ->Build()->execute([$id]);
    }

    public function addTFA(){
        return $this->db->update("admins", ["2fa" => "2fa"])
            ->where([["id", "="]])
            ->Build()->execute(["2fa" => $_SESSION["2FA"], "id" => $_SESSION["id"]]);
    }

    public function changePwd($hash){
        return $this->db->update("admins", ["pwd_hash" => "pwd_hash"])
            ->where([["id", "="]])
            ->Build()->execute(["pwd_hash" => $hash, "id" => $_SESSION["id"]]);
    }
}