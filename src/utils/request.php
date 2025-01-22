<?php

require_once("utils/session.php");
require_once("utils/builder.php");
require_once("utils/cred.php");

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
        http_response_code(302);
        header("location: $url");
        exit();
    }

    static function exceptionHandler($exception)
    {
        error_log("Eccezione non catturata: " . $exception->getMessage());
        Self::load_500_page();
    }

    static function set_exception_handler()
    {
        set_exception_handler("Request::exceptionHandler");
    }


    static function read_upload_file(string $target_dir, string $param, mixed &$error): ?string
    {
        $image_path = null;
        if (!empty($_FILES[$param]["name"])) {
            $target_file = $target_dir . basename($_FILES[$param]["name"]);

            // Controlla se il file è un'immagine reale o un'immagine falsa
            $check = getimagesize($_FILES[$param]["tmp_name"]);
            if ($check === false) {
                $error = "Il file non è un'immagine.";
            }

            // Controlla la dimensione del file
            if ($_FILES[$param]["size"] > (2 * 1024 * 1024)) {
                $error = "Spiacente, il tuo file è troppo grande.";
            }

            // Controlla se $error è impostato su un messaggio di errore
            if (empty($error)) {
                if (move_uploaded_file($_FILES[$param]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    $error = "Spiacente, si è verificato un errore durante il caricamento del tuo file.";
                }
            }
        }
        return $image_path;
    }
};

if (DEFAULT_VARS["DEBUG"] ?? false) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    Request::set_exception_handler();
}
