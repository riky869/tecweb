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

$db = DB::from_env();
$user = Session::get_user();
$movie_id = $_POST["film_id"];

if (empty($user)) {
    header("Location: film.php?id=$movie_id");
    exit();
}

$movie = $db->get_movie($movie_id);

$created = $db->create_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);

header("Location: film.php?id=$movie_id");
exit();
