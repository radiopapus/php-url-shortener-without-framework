<?php

namespace classes;

abstract class BaseModel
{
    public int|null $id;

    abstract public static function getTableName(): string;
}
