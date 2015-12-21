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

if (isset($_REQUEST['url']) AND !empty($_REQUEST['url'])) {
    $url = trim($_REQUEST['url'], '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
    if (Validator::url($url)) {
        $shortener = new UrlShortenerModel($config);
        $insertId = $shortener->add(trim($_REQUEST['url']));
        $shortUrl = $shortener->getShortUrl($insertId);
        echo '<a href="'.$shortUrl.'" target="_blank">'.$shortUrl.'</a>';
        exit;
    } 

    echo 'Wrong url!';
}
