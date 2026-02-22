<?php

namespace Controllers;

class Media
{
    public function out($filename){
        // Load an image from local webp file
        // Images can be converted into webp
        // using imagewebp() function or other
        // online convertors

        // View the loaded image in browser
        // using imagewebp() function
        header('Content-type: image/webp');
        $path = $_SERVER["DOCUMENT_ROOT"] . "/app/uploads/";
        header('Content-Length: ' . filesize($path));
        readfile( $path . basename($filename));
        die();
    }
}