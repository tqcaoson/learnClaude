<?php

if (!function_exists('str_limit')) {
    function str_limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }
        return rtrim(mb_substr($value, 0, $limit, 'UTF-8'), ' ').$end;
    }
}

if (!function_exists('class_basename')) {
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('is_authenticated')) {
    function is_authenticated(): bool
    {
        return auth()->check();
    }
}

if (!function_exists('is_guest')) {
    function is_guest(): bool
    {
        return !auth()->check();
    }
}

if (!function_exists('current_user')) {
    function current_user()
    {
        return auth()->user();
    }
}
