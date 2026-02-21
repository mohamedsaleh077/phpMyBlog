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
}