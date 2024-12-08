<?php

class DB
{
    private mysqli $conn;

    public function __construct($host, $dbname, $user, $pass)
    {
        $conn = mysqli_connect($host, $user, $pass, $dbname, 3306);
        if (!$conn) {
            throw new Exception("Could not connect to database: " . mysqli_connect_error());
        }

        $this->conn = $conn;
    }

    public static function from_env(): Self
    {
        $host = getenv("DB_HOST");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");
        $dbname = getenv("DB_NAME");

        return new Self($host, $dbname, $user, $pass);
    }

    public function close()
    {
        $this->conn->close();
    }

    // TODO: use hashing
    public function get_user_with_password(string $username, string $password): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username=?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$username])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_assoc();
            $res->free();

            if ($row && $row["password"] == $password) {
                return $row;
            }
            return null;
        } else {
            throw new Exception("Could not bind result: " . $stmt->error);
        }
    }

    public function get_user(string $username): mixed
    {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username=?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$username])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_assoc();
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not bind result: " . $stmt->error);
        }
    }

    public function create_user(string $username, string $password, string $name, string $last_name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO user (username, password, name, last_name, is_admin) VALUES (?,?,?,0)");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if ($stmt->execute([
            $username,
            $password,
            $name,
            $last_name,
        ])) {
            return $stmt->affected_rows > 0;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_users(): ?array
    {
        if ($res = $this->conn->query("SELECT * FROM user")) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute query: " . $this->conn->error);
        }
    }

    public function get_movies(): ?array
    {
        if ($res = $this->conn->query("SELECT * FROM movie")) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute query: " . $this->conn->error);
        }
    }

    public function get_movies_by_category(string $category): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie
                          JOIN movie_category ON movie.id = movie_category.movie_id
                          WHERE movie_category.category_name = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$category])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_category(string $name): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM category WHERE name = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$name])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_assoc();
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_movie(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_assoc();
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_movie_categories(int $film_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie_category WHERE movie_id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$film_id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_movie_cast(int $film_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie_cast
                          JOIN people ON movie_cast.person_id = people.id
                          WHERE movie_cast.movie_id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$film_id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_movie_crew(int $film_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM movie_crew
                          JOIN people ON movie_crew.person_id = people.id
                          WHERE movie_crew.movie_id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$film_id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }


    public function get_categories(): ?array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT * FROM category");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function get_reviews(int $film_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM review WHERE movie_id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$film_id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        if ($res = $stmt->get_result()) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }
    }

    public function create_review(int $film_id, string $username, string $title, string $content, int $rating): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO review (
            title,
            content,
            rating,
            username,
            movie_id,
            data
        ) VALUES (?,?,?,?,?,NOW())");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $title,
            $content,
            $rating,
            $username,
            $film_id,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }
}
