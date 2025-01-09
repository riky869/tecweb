<?php

class Session
{
    private static $USER_KEY = "current_user";

    static function start()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
    }

    static function is_logged(): bool
    {
        return Self::get_user() !== null;
    }

    static function set_user(array $user)
    {
        $_SESSION[Self::$USER_KEY] = $user;
    }

    static function get_user(): ?array
    {
        return $_SESSION[Self::$USER_KEY] ?? null;
    }

    static function logout()
    {
        unset($_SESSION[Self::$USER_KEY]);
    }
}
