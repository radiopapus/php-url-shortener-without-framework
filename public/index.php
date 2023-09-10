<?php

require_once ("../error_handler.php");

spl_autoload_register(function ($classname) {
    include_once __DIR__."/../web/" . str_replace("\\", "/", $classname) . '.php';
});

use classes\helpers\Retry;
use classes\response\JsonResponse;
use config\Config;
use urlshortener\exceptions\InvalidUrlException;
use urlshortener\UrlRequest;
use urlshortener\UrlResponse;
use urlshortener\UrlShortenerService;

$config = Config::getInstance();

$pdo = Retry::retry(function () use ($config) {
    return new \PDO(
        $config->getEnvByKey('DATABASE_URL'), $config->getEnvByKey('MYSQL_USER'), $config->getEnvByKey('MYSQL_PASS'), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ]
    );
});

$requestUri = $_SERVER['REQUEST_URI'];
$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($requestUri, "api/urlshort");

if ($postRequest) {
    $service = new UrlShortenerService($pdo, $config);
    $request = new UrlRequest();

    $request->originalUrl = $_POST["originalUrl"] ?? throw new InvalidUrlException("Empty url");
    $model = $service->create($request);

    header('Content-Type: application/json');
    $response = new UrlResponse($model->originalUrl, $model->encodedUrl);
    echo new JsonResponse(true, $response);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $service = new UrlShortenerService($pdo, $config);

    header('HTTP/1.1 301 Moved Permanently');
    header(sprintf("Location: %s", $service->getOriginalUrlByEncodedUrl($requestUri)));
    exit(0);
}

die("unknown route");
