<?php

namespace Models;
use Core\QueryBuilder;
class CategoriesDB
{
    private $sql;
    public function __construct(){
        $this->sql = new QueryBuilder();
    }

    public function getAll(){
        return $this->sql
            ->select("categories", ["*"])
            ->Build()->execute([], true);
    }

    public function add($name, $info){
        return $this->sql
            ->Insert("categories", ["name", "info"])
            ->Build()->Execute([$name, $info]);
    }

    public function del($id){
        return $this->sql
            ->Delete("categories")
            ->where([["id", "="]])
            ->Build()->Execute(["id" => $id]);
    }
}