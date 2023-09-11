<?php

namespace classes\response;

/**
 * Json response wrapper. errId contains uniq id which help find error in logs via search.
 */
final class JsonResponse
{
    public mixed $data;
    public bool $success;
    public string $errMsg;
    public string $errId;

    public function __construct(bool $success, $data)
    {
        $this->success = $success;
        $this->data = $data;
    }

    public function __toString()
    {
        return json_encode([
            "success" => (int)$this->success,
            "data" => $this->data,
            "errMsg" => !$this->success ? ($this->errMsg ?? "") : "",
            "errId" => !$this->success ? ($this->errId ?? uniqid()) : ""
        ]);
    }
}
