<?php

namespace Models;
use Core\QueryBuilder;
use Core\Database;

class ContentDB
{
    private $db;
    public function __construct(){
        $this->db = new QueryBuilder();
    }
    public function getAllContent(){
        return $this->db
            ->select("categories", ["*"])
            ->join("articles", "articles.category_id", "categories.id")
            ->build()->execute([], true);
    }

    public function getContent($id){
        $sql = "SELECT * FROM articles 
                INNER JOIN categories ON articles.category_id =  categories.id 
                WHERE categories.id = :id";
        return Database::fetchAll($sql, [":id" => $id]);
    }

    public function getArticle($slug){
        return $this->db
            ->select("articles", ["*"])
            ->where([["slug", "="]])
            ->build()->execute([$slug], true);
    }
}