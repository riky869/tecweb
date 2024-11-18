<?php

class DB
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

    public function get_user_by_password($username, $password): mixed
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = :username AND password = :password");

        $res = $stmt->execute(["username" => $username, "password" => $password]);
        if ($res) {
            $row = $stmt->fetch();
            return $row;
        }

        return null;
    }

    public function get_movies(): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie");

        $res = $stmt->execute([]);
        if ($res) {
            $row = $stmt->fetchAll();
            return $row;
        }

        return null;
    }
}
