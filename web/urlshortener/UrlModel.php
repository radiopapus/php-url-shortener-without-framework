<?php

namespace urlshortener;

use classes\BaseModel;

class UrlModel extends BaseModel
{
    public string $originalUrl;
    public string $encodedUrl;

    public function __construct(string $originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    public static function getTableName(): string
    {
        return "url";
    }
}
