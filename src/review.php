<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["POST"]);
Session::start();

if (empty($_POST["film_id"]) || empty($_POST["type"])) {
    Request::load_404_page();
}

$user = Session::get_user();
$movie_id = $_POST["film_id"];
$type = $_POST["type"];
$rev_username = $_POST["username"] ?? null;

// parse movie_id from string to int
if (!is_numeric($movie_id) || !ctype_digit($movie_id)) {
    Request::load_404_page();
}
$movie_id = intval($movie_id);

if (!empty($user)) {
    $db = DB::from_env();

    // check if movie id exists
    if (!$db->get_movie($movie_id)) {
        Request::load_404_page();
    }

    switch ($type) {
        case "delete":
            // if the delete request is made by an admin, the review to delete is the one of the user specified in the form
            if (!empty($rev_username) && !empty($user) && $user["is_admin"]) {
                $rev_username_to_delete = $rev_username;
            }
            // if the delete request is made by a user, the review to delete is the one of the user that made the request 
            else {
                $rev_username_to_delete = $user["username"];
            }
            $deleted = $db->delete_review($movie_id, $rev_username_to_delete);
            break;
        case "create":
            $created = $db->create_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);
            break;
        case "modify":
            $modified = $db->modify_review($movie_id, $user["username"], $_POST["title"], $_POST["content"], $_POST["rating"]);
            break;
    }

    $db->close();
}
// TODO: else redirect to error probably

$location = "/film.php?id=$movie_id";
if (!empty($_POST["cat"])) {
    $location .= "&cat=" . $_POST["cat"];
}

Request::redirect($location);
