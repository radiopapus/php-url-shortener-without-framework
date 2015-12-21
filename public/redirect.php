<?php
namespace ViktorZharina\UrlShortener;
require_once('../config/config.php');
require_once('../classes/UrlShortenerModel.php');

if (isset($_GET['hash'])) {
    $shortener = new UrlShortenerModel($config);
    $id = $shortener->getIdByHash($_GET['hash']);
    $srcUrl = $shortener->getSourceUrlById($id, array('srcurl'));
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' .  $srcUrl);
    return;    
}
