<?php
require_once('../config/config.php');
require_once('../classes/UrlShortenerModel.php');

if (isset($_GET['hash'])) {
    $pdo = new \PDO($config['dsn'], $config['db_user'], $config['db_pass'], $config['db_opt']);
    $shortener = new UrlShortenerModel($pdo, $config);
    $id = $shortener->getIdByHash($_GET['hash']);
    $srcUrl = $shortener->getSourceUrlById($id);
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' .  $srcUrl);
    return;
}