<?php

class Request
{
    private static function method(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    static function is_post(): bool
    {
        return Self::method() == "POST";
    }

    static function is_get(): bool
    {
        return Self::method() == "GET";
    }

    static function allowed_methods(array $methods)
    {
        if (!in_array(Self::method(), array_map(fn(string $method): string => strtoupper($method), $methods))) {
            http_response_code(405);
            exit();
        }
    }
};
