<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["POST"]);
Session::start();

if (empty($_POST["film_id"])) {
    header("Location: categories.php");
    exit();
}

$user = Session::get_user();
$movie_id = $_POST["film_id"];

if (!empty($user)) {
    $db = DB::from_env();
    $movie = $db->get_movie($movie_id);
    $created = $db->create_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);
    $db->close();
}

$location = "Location: film.php?id=$movie_id";
if (!empty($_POST["cat"])) {
    $location .= "&cat=" . $_POST["cat"];
}

header($location);
exit();
