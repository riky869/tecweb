<?php

require_once("utils/session.php");
require_once("utils/builder.php");

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
            Self::load_404_page();
        }
    }

    static function load_403_page()
    {
        require("403.php");
        exit();
    }

    static function load_404_page()
    {
        require("404.php");
        exit();
    }

    static function load_500_page()
    {
        require("500.php");
        exit();
    }

    static function redirect($url)
    {
        header("location: $url");
        http_response_code(302);
        exit();
    }
};
