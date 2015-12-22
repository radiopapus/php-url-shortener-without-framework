<?php

function my_exception_handler($e) {
    $data = array('success' => 0, 'errMsg' => 'System error');
    header('Content-Type: application/json');
    $resp = json_encode($data);
    echo $resp;
    // @todo write toLog
    exit;
}

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    if (!(error_reporting() & $errno)) {
        // Этот код ошибки не входит в error_reporting
        return;
    }
    throw new Exception($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler("exception_error_handler");
set_exception_handler('my_exception_handler');

class UrlShortenerException extends \Exception {
    
}