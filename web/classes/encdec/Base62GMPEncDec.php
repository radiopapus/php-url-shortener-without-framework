<?php

namespace classes\encdec;

/**
 * Base62 encoder decoder. Fastest and shorter than Base64.
 */
class Base62GMPEncDec implements UrlEncoderDecoder
{

    public function encode(string $originalValue): string
    {
        return gmp_strval($originalValue, 62);
    }

    public function decode(string $encodedValue): string|false
    {
        $value = gmp_init($encodedValue, 62);
        return gmp_strval($value);
    }
}
