<?php

namespace classes\response;

abstract class AbstractResponse {
    protected mixed $data;
    protected string $errMsg;
    protected string $errId;
}
