<?php

namespace Controllers;
use Core\Controller;
use Models\ContentDB;
class Content
{
    public function index(){
        Controller::view("content");
    }

    public function all(){
        $db = new ContentDB();
        $result = $db->getAllContent()[0];
        $groupedData = [];

        foreach ($result as $row) {
            $catId = $row['category_id'];
            if (!isset($groupedData[$catId])) {
                $groupedData[$catId] = [
                    'category_name' => $row['name'],
                    'category_info' => $row['info'],
                    'posts' => []
                ];
            }

            // بنضيف المقال جوه قسم الـ posts بتاع الكاتيجوري ده
            $groupedData[$catId]['posts'][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'thumbnail' => $row['thumbnail']
            ];
        }

// ابعت الداتا للـ Frontend كـ JSON
        header('Content-type: application/json');
        echo json_encode(array_values($groupedData));
    }

    public function post($slug){
        $db = new ContentDB();
        $r = $db->getArticle($slug);
        Controller::view("post", $r);
    }

    public function getCategory($id){
        $db = new ContentDB();
        if(is_int($id)){
            http_response_code(404);
            die();
        }
        $r = $db->getContent($id);
        header('Content-type: application/json');
        echo json_encode(array_values($r));
    }
}