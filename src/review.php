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

if (empty($_POST["modify"])) {
    header("Location: categories.php");
    exit();
}

$user = Session::get_user();
$movie_id = $_POST["film_id"];
$to_modify = $_POST["modify"] === "true";

if (!empty($user)) {
    $db = DB::from_env();

    // check if movie id exists
    if (!$db->get_movie($movie_id)) {
        Request::load_404_page();
    }

    if (!$to_modify) {
        $created = $db->create_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);
    } else {
        $modified = $db->modify_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);
    }
    $db->close();
}
// TODO: else redirect to error probably

$location = "Location: film.php?id=$movie_id";
if (!empty($_POST["cat"])) {
    $location .= "&cat=" . $_POST["cat"];
}

header($location);
http_response_code(303);
exit();
