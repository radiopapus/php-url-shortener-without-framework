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
        return;
    }
    throw new Exception($errstr, $errno);
}

set_error_handler("exception_error_handler");
set_exception_handler('my_exception_handler');

class UrlShortenerException extends \Exception {
    
}