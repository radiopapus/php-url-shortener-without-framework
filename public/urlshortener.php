<?php
/**
 * Website: http://viktor.zharina.info
 *
 * Licensed under The MIT License
 *
 * @author ViktorZharina <viktorz1986@gmail.com>
 * @version 0.1
 */

/**
 * @todo  не сохранять ссылки 404 = 0
 */

namespace ViktorZharina\UrlShortener;
require_once('../config/config.php');
require_once('../classes/UrlShortenerModel.php');
require_once('../classes/validators/Validator.php');
require_once('../classes/Response.php');

if (isset($_REQUEST['url']) AND !empty($_REQUEST['url'])) {
    $url = trim($_REQUEST['url'], '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
    $response = new Response();
    $result = array('success' => 0, 'shortUrl' => '', 'errMsg' => 'Wrong url');
    if (Validator::url($url)) {
        $shortener = new UrlShortenerModel($config);
        $insertId = $shortener->add($url);
        $shortUrl = $shortener->getShortUrl($insertId);
        $result = array('success' => 1, 'shortUrl' => $shortUrl, 'errMsg' => '');
        echo $response->returnJson($result);
        exit;
    }

    echo $response->returnJson($result);
}
