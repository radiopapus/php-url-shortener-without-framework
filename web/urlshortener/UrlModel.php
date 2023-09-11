<?php

namespace urlshortener;

use classes\BaseModel;
use classes\encdec\UrlEncoderDecoder;
use config\Config;

class UrlModel extends BaseModel
{
    public string $originalUrl;
    private Config $config;

    public function __construct(string $originalUrl, Config $config)
    {
        $this->originalUrl = $originalUrl;
        $this->config = $config;
    }

    private function getEncodedId(): string
    {
        return $this->config->getUrlEncoderDecoder()->encode($this->id);
    }

    public function getAbsoluteShortUrl(): string
    {
        $host = $this->config->getEnvByKey("APP_HOST") ?? "http://localhost";
        $port = $this->config->getEnvByKey("APP_PORT") ?? "8000";

        return sprintf("%s:%s/%s", $host, $port, $this->getEncodedId());
    }

    public static function getTableName(): string
    {
        return "url";
    }
}
