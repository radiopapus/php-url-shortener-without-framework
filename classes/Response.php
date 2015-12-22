<?php
class Response {
    public function returnJson($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }
}