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

    static function post_param(string $name): string
    {
        return $_POST[$name];
    }

    static function get_param(string $name): string
    {
        return $_GET[$name];
    }

    static function is_get(): bool
    {
        return Self::method() == "GET";
    }

    static function allowed_methods(array $methods)
    {
        $methods = array_map(fn(string $method): string => strtoupper($method), $methods);
        if (!in_array(Self::method(), $methods)) {
            http_response_code(404);
            exit();
        }
    }
};
