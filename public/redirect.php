<?php
require_once('../config/config.php');
require_once('../classes/UrlShortenerModel.php');

if (isset($_GET['hash'])) {
    $shortener = new UrlShortenerModel($config);
    $id = $shortener->getIdByHash($_GET['hash']);
    $srcUrl = $shortener->getSourceUrlById($id);
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' .  $srcUrl);
    return;
}