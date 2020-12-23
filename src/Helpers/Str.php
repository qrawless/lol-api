<?php


namespace Qrawless\Lol\Helpers;


class Str
{
    /**
     * @param string $string
     * @param array $replacements
     * @return string
     */
    public static function Replace(string $string, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $string = str_replace(':'.$key, $value, $string);
        }
        return $string;
    }
}