<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");
require_once("utils/input.php");

Request::allowed_methods(["POST"]);
Session::start();

if (empty($_POST["film_id"]) || empty($_POST["type"])) {
    Request::load_404_page();
}

$user = Session::get_user();

if (empty($user)) {
    Request::load_403_page();
}

$movie_id = $_POST["film_id"];
$type = $_POST["type"];
$rev_username = $_POST["username"] ?? null;

// parse movie_id from string to int
if (!is_numeric($movie_id) || !ctype_digit($movie_id)) {
    Request::load_404_page();
}
$movie_id = intval($movie_id);

$db = DB::from_env();

// check if movie id exists
if (!$db->get_movie($movie_id)) {
    Request::load_404_page();
}

$error = null;

if ($type == "create" || $type == "modify") {
    if (empty($_POST["title"]) || empty($_POST["content"]) || empty($_POST["rating"])) {
        $error = "Compila tutti i campi";
    } else {
        $rating = pulisciInput($_POST["rating"]);
        $content = pulisciInput($_POST["content"]);
        $title = pulisciInput($_POST["title"]);

        if (!is_numeric($rating) || !ctype_digit($rating) || $rating < 1 || $rating > 10) {
            $error = "Il rating deve essere un numero intero compreso tra 1 e 10";
        } else  if (strlen($title) < 3) {
            $error = "Il titolo deve essere lungo almeno 3 caratteri";
        } else if (strlen($content) < 5) {
            $error = "Il contenuto deve essere lungo almeno 10 caratteri";
        }
    }
} else if ($type == "delete") {
    // if the delete request is made by an admin, the review to delete is the one of the user specified in the form
    if (!empty($rev_username) && !empty($user) && $user["is_admin"]) {
        $rev_username_to_delete = $rev_username;
    }
    // if the delete request is made by a user, the review to delete is the one of the user that made the request 
    else {
        $rev_username_to_delete = $user["username"];
    }
} else {
    Request::load_403_page();
}

try {
    if (empty($error)) {
        switch ($type) {
            case "delete":
                $deleted = $db->delete_review($movie_id, $rev_username_to_delete);
                break;
            case "create":
                $created = $db->create_review($movie_id, $user["username"], $title, $content, $rating);
                break;
            case "modify":
                $modified = $db->modify_review($movie_id, $user["username"], $title, $content, $rating);
                break;
        }
        $db->close();
    }
} catch (Exception $e) {
    Request::load_500_page();
}

$_SESSION["review_error"] = $error;

$location = "film.php?id=$movie_id";
if (!empty($_POST["cat"])) {
    $location .= "&cat=" . $_POST["cat"];
}
$location .= "#recensioni";

Request::redirect($location);
