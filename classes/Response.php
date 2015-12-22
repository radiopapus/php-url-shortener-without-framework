<?php
namespace ViktorZharina\UrlShortener;
class Response {
    public function returnJson($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
}