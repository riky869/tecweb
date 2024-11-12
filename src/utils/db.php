<?php

class DbConnection
{

    private PDO $conn;

    public function __construct($host, $dbname, $user, $pass)
    {
        try {
            $this->conn = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH]);
        } catch (PDOException $e) {
            throw new Exception("Errore di connessione al database: {$e}");
        }
    }

    public static function from_env(): Self
    {
        $host = getenv("DB_HOST");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");
        $dbname = getenv("DB_NAME");

        return new Self($host, $dbname, $user, $pass);
    }

    public function get_conn(): PDO
    {
        return $this->conn;
    }
}
