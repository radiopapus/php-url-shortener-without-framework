<?php

use classes\response\JsonResponse;
use JetBrains\PhpStorm\NoReturn;
use urlshortener\exceptions\InvalidUrlException;

#[NoReturn] function my_exception_handler(\Exception $e): void
{
    var_dump($e);
    $resp = new JsonResponse(false, []);

    [$resp->errId, $resp->errMsg, $code] = match (true) {
        $e instanceof InvalidUrlException => [uniqid(), $e->getMessage(), 400],
        $e instanceof \Exception => [uniqid(), "Service Error", 500],
    };

    fwrite(fopen('php://stderr', 'w'), $e . PHP_EOL);

    http_response_code($code);
    header('Content-Type: application/json');
    echo $resp;
    exit;
}

/**
 * @throws \Exception
 */
#[NoReturn] function exception_error_handler($errno, $errstr, $errfile, $errline): void
{
    if (!(error_reporting() & $errno)) {
        return;
    }
    throw new \Exception($errstr, $errno);
}

set_error_handler("exception_error_handler");
set_exception_handler('my_exception_handler');
