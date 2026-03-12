<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait DynamicAttributeMapper
{
    protected static $attributeMapping = [];

    public static function setAttributeMapping(array $mapping)
    {
        static::$attributeMapping = $mapping;
    }

    public function getAttribute($key)
    {
        // Check if the key exists in the mapping
        if (isset(static::$attributeMapping[$key])) {
            $key = static::$attributeMapping[$key];
        }
        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        // Check if the key exists in the mapping
        if (isset(static::$attributeMapping[$key])) {
            $key = static::$attributeMapping[$key];
        }

        return parent::setAttribute($key, $value);
    }
}
