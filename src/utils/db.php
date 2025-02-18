<?php

require_once("utils/cred.php");

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
        $host = getenv("DB_HOST") != false ? getenv("DB_HOST") : DEFAULT_VARS["DB_HOST"];
        $user = getenv("DB_USER") != false ? getenv("DB_USER") : DEFAULT_VARS["DB_USER"];
        $pass = getenv("DB_PASS") != false ? getenv("DB_PASS") : DEFAULT_VARS["DB_PASS"];
        $dbname = getenv("DB_NAME") != false ? getenv("DB_NAME") : DEFAULT_VARS["DB_NAME"];

        return new Self($host, $dbname, $user, $pass);
    }

    public function close()
    {
        $this->conn->close();
    }

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
            if ($row && password_verify($password, $row["password"])) {
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
        $stmt = $this->conn->prepare("INSERT INTO user (username, password, name, last_name, is_admin) VALUES (?,?,?,?,0)");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

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
        if ($res = $this->conn->query("SELECT * FROM user ORDER BY username ASC")) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute query: " . $this->conn->error);
        }
    }

    public function get_movies(): ?array
    {
        if ($res = $this->conn->query("SELECT * FROM movie ORDER BY name ASC")) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute query: " . $this->conn->error);
        }
    }

    public function get_persons(): ?array
    {
        if ($res = $this->conn->query("SELECT * FROM people ORDER BY name ASC")) {
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $res->free();
            return $row;
        } else {
            throw new Exception("Could not execute query: " . $this->conn->error);
        }
    }

    public function get_movies_by_category(string $category): ?array
    {
        $stmt = $this->conn->prepare("SELECT *, movie.name as movie_name, movie.html_name as movie_html_name FROM movie
                          JOIN movie_category ON movie.id = movie_category.movie_id
                          JOIN category ON category.name = movie_category.category_name
                          WHERE movie_category.category_name = ?
                          ORDER BY movie_name ASC");
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
        $stmt = $this->conn->prepare("SELECT category.* FROM movie_category JOIN category ON category_name = category.name WHERE movie_id = ?");
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
        $stmt = $this->conn->prepare("SELECT DISTINCT * FROM category ORDER BY name ASC");
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

    public function get_film_reviews(int $film_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM review WHERE movie_id = ? ORDER BY data DESC");
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

    public function get_reviews_by_user(string $username): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM review JOIN movie ON movie.id = review.movie_id WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$username])) {
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

    public function get_review_by_user_and_movie(string $username, int $movie_id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM review WHERE username = ? AND review.movie_id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$username, $movie_id])) {
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

    public function create_film(string $name, ?string $original_name, ?string $original_language, ?string $release_date, ?int $runtime, string $phase, ?int $budget, ?int $revenue, string $description, ?string $image_path): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO movie (
            name,
            original_name,
            original_language,
            release_date,
            runtime,
            phase,
            budget,
            revenue,
            description,
            image_path
        ) VALUES (?,?,?,?,?,?,?,?,?,?)");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $name,
            $original_name,
            $original_language,
            $release_date,
            $runtime,
            $phase,
            $budget,
            $revenue,
            $description,
            $image_path,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function create_person(string $name, ?string $profile_image): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO people (name, profile_image) VALUES (?, ?)");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $name,
            $profile_image,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function add_category_to_movie(int $movie_id, string $category_name): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO movie_category (movie_id, category_name) VALUES (?, ?)");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $category_name,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function delete_category_from_movie(int $movie_id, string $category_name): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM movie_category WHERE movie_id = ? AND category_name = ?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $category_name,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function delete_movie(int $movie_id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM movie WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([$movie_id])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function add_person_to_movie_cast(int $movie_id, int $person_id, string $role): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO movie_cast (movie_id, person_id, role) VALUES (?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $person_id,
            $role,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function delete_person_from_movie_cast(int $movie_id, int $person_id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM movie_cast WHERE movie_id = ? AND person_id = ?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $person_id,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function add_person_to_movie_crew(int $movie_id, int $person_id, string $role): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO movie_crew (movie_id, person_id, role) VALUES (?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $person_id,
            $role,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function delete_person_from_movie_crew(int $movie_id, int $person_id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM movie_crew WHERE movie_id = ? AND person_id = ?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $movie_id,
            $person_id,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function modify_review(int $film_id, string $username, string $title, string $content, int $rating): bool
    {

        $stmt = $this->conn->prepare("UPDATE review SET title=?, content=?, rating=?, data=NOW() WHERE movie_id=? AND username=?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $title,
            $content,
            $rating,
            $film_id,
            $username,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function delete_review(int $film_id, string $username): bool
    {

        $stmt = $this->conn->prepare("DELETE FROM review WHERE movie_id=? AND username=?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $film_id,
            $username,
        ])) {
            throw new Exception("Could not execute statement: " . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    public function get_last_reviews(int $num_revs = 5): ?array
    {

        $stmt = $this->conn->prepare("SELECT review.*, movie.name, movie.html_name FROM review JOIN movie ON review.movie_id = movie.id ORDER BY data DESC LIMIT ?");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $num_revs,
        ])) {
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

    public function get_top_films(int $num = 5): ?array
    {
        $stmt = $this->conn->prepare("
        SELECT movie.*, AVG(review.rating) as avg_rating FROM review JOIN movie ON review.movie_id = movie.id GROUP BY movie.id ORDER BY avg_rating DESC, movie.name ASC LIMIT ?
        ");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $num,
        ])) {
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

    public function get_incoming_films(int $num = 5): ?array
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM movie ORDER BY release_date DESC, name ASC LIMIT ?
        ");

        if (!$stmt) {
            throw new Exception("Could not prepare statement: " . $this->conn->error);
        }

        if (!$stmt->execute([
            $num,
        ])) {
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
}
