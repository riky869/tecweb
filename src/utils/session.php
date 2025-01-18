<?php

require_once("utils/cred.php");

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

    static function always_logged()
    {
        Self::set_user([
            "id" => 1,
            "name" => "mario",
            "last_name" => "rossi",
            "username" => "admin",
            "password" => '$2a$12$.w3WfJMmXnV3Ap3H598wMOk/0bd7gk/OvCSx8QngaNkIs/VtgHDwq',
            "is_admin" => true,
        ]);
    }
}

if (DEFAULT_VARS["ALWAYS_LOGGED"] ?? false)
    Session::always_logged();
