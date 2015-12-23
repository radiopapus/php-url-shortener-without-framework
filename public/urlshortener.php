<?php
require_once('../config/config.php');
require_once('../classes/UrlShortenerModel.php');
require_once('../classes/exceptions/UrlShortenerException.php');
require_once('../classes/Response.php');

$response = new Response();

if (isset($_REQUEST['url']) AND !empty($_REQUEST['url'])) {
    $url = trim($_REQUEST['url'], '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
    $pdo = new \PDO($config['dsn'], $config['db_user'], $config['db_pass'], $config['db_opt']);
    $shortener = new UrlShortenerModel($pdo, $config);
    $insertId = $shortener->add($url);
    $shortUrl = $shortener->getShortUrl($insertId);
    $result = array('success' => 1, 'shortUrl' => $shortUrl, 'errMsg' => '');
    echo $response->returnJson($result);
    exit;
}

$result = array('success' => 0, 'shortUrl' => '', 'errMsg' => 'Url not defined');
echo $response->returnJson($result);
