<?php

namespace urlshortener;

class UrlResponse
{
    public string $originalUrl;
    public string $shortUrl;

    public function __construct(string $originalUrl, string $shortUrl) {
        $this->originalUrl = $originalUrl;
        $this->shortUrl = $shortUrl;
    }

    public function fromModel(UrlModel $model): self {
        return new UrlResponse($model->originalUrl, $model->encodedUrl);
    }
}
