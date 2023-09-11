<?php

namespace urlshortener;

use classes\DB;
use classes\helpers\Retry;
use config\Config;
use Exception;
use urlshortener\exceptions\InvalidUrlException;
use urlshortener\exceptions\NotFoundException;

/**
 * Url shortener service.
 */
class UrlShortenerService
{
    private DB $db;
    private Config $config;

    public function __construct(DB $db, Config $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    /**
     * Check if url available
     *
     * @param $url
     * @return bool
     */
    private function ifNotAvailable($url): bool
    {
        $headers = @get_headers($url);
        return !$headers || strpos($headers[0], "404");
    }

    private function log(string $message): void
    {
        fwrite(fopen("php://stdout", "w"), $message . PHP_EOL);
    }

    private function validate(UrlRequest $request)
    {
        $this->log("Validation for url $request->originalUrl started.");

        match (true) {
            empty($request->originalUrl) => throw new InvalidUrlException("Url is empty."),
            !parse_url($request->originalUrl) => throw new InvalidUrlException("Check url format please."),
            $this->ifNotAvailable($request->originalUrl) => throw new InvalidUrlException("Url resource does not exist."),
            default => ""
        };
        $this->log("Validation is ok.");
    }

    /**
     * Add url to storage with retries if storage unavailable.
     *
     * Use id as identifier for original url. Encode id via encoder (default Base62 based on gmp stuff).
     * Don"t check existence url by original url to avoid additional query and indexing.
     * Validate format original url, availability and emptiness.
     *
     * @param UrlRequest $request
     * @return UrlModel
     * @throws InvalidUrlException
     * @throws Exception
     */
    public function create(UrlRequest $request): UrlModel
    {
        $this->validate($request);

        $model = new UrlModel($request->originalUrl, $this->config);

        $this->log("Write to database.");
        $db = $this->db;
        Retry::retry(function () use ($request, $db) {
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (:originalUrl)",
                UrlModel::getTableName(), "originalUrl",
            );

            return $db->insert($sql, ["originalUrl" => $request->originalUrl]);
        });
        $this->log("Write to database - OK.");

        $model->id = $this->db->lastInsertId();

        return $model;
    }

    private function getEncodeIdFromPath(string $path): bool|string
    {
        $linkArray = explode("/", $path);

        return end($linkArray);
    }

    /**
     * Decode and returns original url by /path.
     *
     * Get encoded url from path (id), decode and fetch from db. /aHxas -> 1000003
     *
     * @throws InvalidUrlException
     * @throws Exception
     */
    public function getOriginalUrl(string $path): string
    {
        $encodedId = $this->getEncodeIdFromPath($path);

        if (empty($encodedId)) {
            throw new InvalidUrlException("Incorrect url in path.");
        }
        $this->log("EncodedId from path $encodedId.");

        $id = $this->config->getUrlEncoderDecoder()->decode($encodedId);

        $this->log("Decoded id $id.");

        $db = $this->db;
        $originalUrl = Retry::retry(function () use ($id, $db) {
            $sql = sprintf("SELECT originalUrl FROM %s WHERE id=:id", UrlModel::getTableName());
            return $db->fetchColumn($sql, ["id" => $id]);
        });

        if (empty($originalUrl)) {
            throw new NotFoundException("Url not found.");
        }

        $this->log("OriginalUrl from database $originalUrl.");

        return $originalUrl
            ?? throw new InvalidUrlException("Incorrect url: check your link, probably out of date");
    }
}
