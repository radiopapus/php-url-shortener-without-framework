<?php

namespace classes\helpers;

/**
 * Simple helper for retry feature.
 */
class Retry
{
    /**
     * @throws \Exception
     */
    static function retry($f, $delay = 2, $retries = 3): mixed
    {
        try {
            return $f();
        } catch (\Exception $e) {
            if ($retries > 0) {
                sleep($delay);
                return self::retry($f, $delay, $retries - 1);
            } else {
                throw $e;
            }
        }
    }
}
