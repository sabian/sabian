<?php
namespace Sabian\Helpers;

class Resource
{
    private static function path($name)
    {
        $path = [__DIR__, '..', '..', 'resources', $name . '.php'];

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    public static function load($name)
    {
        $data = [];

        if (file_exists(self::path($name))) {
            return require_once self::path($name);
        }

        return $data;
    }
}