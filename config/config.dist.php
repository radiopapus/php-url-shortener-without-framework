<?php

$config = array();
$config['env'] = 'prod';
$config['site'] = 'http://php7dev/';
$config['possibleChars'] = 'abcdefghijklmnopqrstuvwxyz';
$config['dsn'] = 'mysql:host=localhost;dbname=urlshortener';
$config['db_user'] = 'root';
$config['db_pass'] = 'toor';
$config['db_opt'] = array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                );