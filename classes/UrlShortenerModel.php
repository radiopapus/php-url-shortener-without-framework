<?php 
// CREATE DATABASE url_shortener;
// USE url_shortener;
// DROP TABLE IF EXISTS `url`;
// CREATE TABLE IF NOT EXISTS `url` (
//   `id` bigint(20) NOT NULL AUTO_INCREMENT,
//   `srcurl` text CHARACTER SET utf8 NOT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;

namespace ViktorZharina\UrlShortener;
class UrlShortenerModel {
    private $pdo;
    private $config = array();
    const TABLE = 'url';

    public function __construct($config, $opt = array()) {
        $this->config = $config;
        $dsn = $config['dsn'];
        $user = $config['db_user'];
        $pass = $config['db_pass'];

        if (empty($opt)) {
            $opt = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            );
        }

        $this->pdo = new \PDO($dsn, $user, $pass, $opt);
    }

    public function add($srcUrl) {
        $id = $this->hasRow($srcUrl);

        if ($id) {
            return $id;
        }
        
        $sql = sprintf("INSERT INTO %s (%s) VALUES (:srcurl)", self::TABLE, 'srcurl');
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':srcurl', $srcUrl);
        $stm->execute();
        return $this->pdo->lastInsertId();
    }

    public function getSourceUrlById($id, array $columns) {
        $cols = implode(',', $columns);
        $sql = sprintf("SELECT srcurl FROM %s WHERE id = :id", self::TABLE);
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':id', $id);
        $stm->execute();
        $result = $stm->fetch(\PDO::FETCH_ASSOC);
        return (isset($result['srcurl']) AND !empty($result['srcurl'])) ? $result['srcurl'] : null;
    }

    public function getShortUrl($id) {
        $site = $this->config['site'];
        $hash = $this->getHashById($id);
        return $site.$hash;
    }

    public function hasRow($srcUrl) {
        $sql = sprintf("SELECT id FROM %s WHERE srcurl = :srcurl", self::TABLE);
        $stm = $this->pdo->prepare($sql);
        $stm->bindParam(':srcurl', $srcUrl);
        $stm->execute();
        $result = $stm->fetch(\PDO::FETCH_ASSOC);
        if (isset($result['id']) AND !empty($result['id'])) {
            return $result['id'];
        }

        return 0;
    }

    public function getHashById($id) {
        $possibleChars = $this->config['possibleChars'];
        $strl = strlen($id);
        $hash = '';
        for ($pos = 0; $pos < $strl; $pos++) {
            $val = (int) $id[$pos];
            $hash .= $possibleChars[$val];
        }
        return $hash;
    }

    public function getIdByHash($hash) {
        $possibleChars = $this->config['possibleChars'];
        $strl = strlen($hash);
        $n = '';
        for ($pos = 0; $pos < $strl; $pos++) {
            $n .= strpos($possibleChars, $hash[$pos]);
        }
        return (int) $n;
    }
}