<?php

namespace classes\encdec;

interface UrlEncoderDecoder
{
    public function encode(string $originalValue): string;

    public function decode(string $encodedValue): string|false;
}
