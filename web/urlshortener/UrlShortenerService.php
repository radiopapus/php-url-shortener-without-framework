<?php

namespace urlshortener;

use classes\helpers\Retry;
use config\Config;
use Exception;
use PDO;
use urlshortener\exceptions\InvalidUrlException;

/**
 * Url shortener service.
 */
class UrlShortenerService
{
    private PDO $pdo;
    private Config $config;
    private string $salt = "s";

    public function __construct(PDO $pdo, Config $config)
    {
        $this->pdo = $pdo;
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

    /**
     * Add url to storage with retries if storage unavailable.
     *
     * Use id as identifier for original url. Encode id via encoder (default Base62 based on gmp stuff).
     * Don"t check existence url by original url to avoid additional query and indexing.
     * Validate format original url, availability and emptiness.
     *
     * In addition add salt to encoded string to protect from bruteforce.
     *
     * @param UrlRequest $request
     * @return UrlModel
     * @throws InvalidUrlException
     * @throws Exception
     */
    public function create(UrlRequest $request): UrlModel
    {
        $this->log("Validation for url $request->originalUrl started");

        match (true) {
            empty($request->originalUrl) => throw new InvalidUrlException("Url is empty"),
            !parse_url($request->originalUrl) => throw new InvalidUrlException("Check url format please."),
            $this->ifNotAvailable($request->originalUrl) => throw new InvalidUrlException("Url resource does not exist."),
            default => ""
        };
        $this->log("Validation is ok");

        $model = new UrlModel($request->originalUrl);

        $this->log("Write to database");
        Retry::retry(function () use ($request) {
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (:originalUrl)",
                UrlModel::getTableName(), "originalUrl",
            );

            $stm = $this->pdo->prepare($sql);
            $stm->execute(["originalUrl" => $request->originalUrl]);
        });
        $this->log("Write to database - OK");

        $host = $this->config->getEnvByKey("APP_HOST") ?? "http://localhost";
        $port = $this->config->getEnvByKey("APP_PORT") ?? "8000";

        $model->id = $this->pdo->lastInsertId();

        $encoded = $this->config
            ->getUrlEncoderDecoder()
            ->encode($model->id);

        $this->log("Encoded value is $encoded, salt is $this->salt");

        $model->encodedUrl = sprintf("%s:%s/%s%s", $host, $port, $encoded, $this->salt); // add salt to avoid bruteforce
        return $model;
    }

    /**
     * Returns original url by /path. Substract salt (by default last character)
     *
     * @throws InvalidUrlException
     * @throws Exception
     */
    public function getOriginalUrlByEncodedUrl(string $path): string
    {
        $this->log("Absolute url is $path");
        $linkArray = explode("/", $path);

        $encodedId = end($linkArray);
        $this->log("Encoded originalUrl path $encodedId");

        $id = $this->config->getUrlEncoderDecoder()->decode(
            substr($encodedId, 0, 0 - (strlen($this->salt)))
        );

        $this->log("Decoded id $id");

        $result = Retry::retry(function () use ($id) {
            $sql = sprintf(
                "SELECT `originalUrl` FROM %s WHERE `id`=:id LIMIT 1", UrlModel::getTableName()
            );

            $stm = $this->pdo->prepare($sql);
            $stm->execute(["id" => $id]);

            return $stm->fetch();
        });

        $this->log("Result form database " . implode(",", $result));

        return $result["originalUrl"]
            ?? throw new InvalidUrlException("Incorrect url: check your link, probably out of date");
    }
}
