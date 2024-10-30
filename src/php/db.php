<?php

class DbConnection
{

    private PDO $conn;

    public function __construct()
    {
        $host = getenv("DB_HOST");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");
        $dbname = getenv("DB_NAME");

        try {
            $this->conn = new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
        } catch (PDOException $e) {
            throw new Exception("Errore di connessione al database: {$e}");
        }
    }
}
