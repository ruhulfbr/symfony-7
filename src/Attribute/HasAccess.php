<?php

//Src/Attribute/HasAccess.php
namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class HasAccess
{
    const PUBLIC = 'public';
    const PROTECTED = 'protected';
    const PRIVATE = 'private';
    public string $level;

    public function __construct(string $level)
    {
        if (!in_array($level, [self::PUBLIC, self::PROTECTED, self::PRIVATE])) {
            throw new \InvalidArgumentException("Invalid access level specified: $level");
        }
        $this->level = $level;
    }

}