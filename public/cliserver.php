<?php

if (php_sapi_name() !== 'cli-server') {
    die('this is only for the php development server');
}

if (is_file($_SERVER['DOCUMENT_ROOT'].'/'.$_SERVER['SCRIPT_NAME'])) {
    // static files
    return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';

require 'index.php';
