<?php

namespace config;

use classes\encdec\Base62GMPEncDec;
use classes\encdec\UrlEncoderDecoder;

/**
 * Singleton container for storing configuration.
 */
class Config
{

    /**
     * @var Config|null
     */
    private static ?Config $_instance = null;

    /**
     * @var array
     */
    private array $config = [];

    /**
     * Config constructor.
     */
    private function __construct()
    {
        foreach (getenv() as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * Returns the instance.
     *
     * @static
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Get a config item.
     *
     * @param string $key
     * @return ?string
     */
    public function getEnvByKey(string $key): ?string
    {
        return $this->config[$key];

    }

    public function getUrlEncoderDecoder(): UrlEncoderDecoder
    {
        return new Base62GMPEncDec();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public function __destruct()
    {
        self::$_instance = null;
    }
}
