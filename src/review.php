<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");
require_once("utils/input.php");
require_once("utils/input.php");

Request::allowed_methods(["POST"]);
Session::start();

$error = [];
$checks = [
    'mod_rec_title_err' => [
        'err_name' => "titolo",
        'optional' => false,
        'checks' => $commonChecks['rec_title']
    ],
    'mod_rec_content_err' => [
        'err_name' => "contenuto",
        'optional' => false,
        'checks' => $commonChecks['rec_content']
    ],
    'mod_rec_rating_err' => [
        'err_name' => "valutazione",
        'optional' => false,
        'checks' => $commonChecks['rec_rating']
    ],

];

$user = Session::get_user();

if (empty($user)) {
    Request::load_403_page();
}

if (empty($_POST["film_id"]) || empty($_POST["type"])) {
    Request::load_404_page();
}


$movie_id = $_POST["film_id"];
$type = $_POST["type"];
$cat = $_POST["cat"] ?? null;

if (!is_numeric($movie_id)) {
    Request::load_404_page();
}
$movie_id = intval($movie_id);

if ($type == "create" || $type == "modify") {
    if (empty($_POST["title"]) || empty($_POST["content"]) || empty($_POST["rating"])) {
        $error[] = "Compila tutti i campi";
    }

    $title = validate($_POST["title"], $checks["mod_rec_title_err"], $error);
    $content = validate($_POST["content"], $checks["mod_rec_content_err"], $error);
    $rating = validate($_POST["rating"], $checks["mod_rec_rating_err"], $error);
} else if ($type == "delete") {
    $rev_username = $_POST["username"] ?? null;
    if (empty($rev_username)) {
        Request::load_404_page();
    }

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

if (empty($error)) {

    $db = DB::from_env();
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

$_SESSION["review_error"] = $error;

$location = "film.php?id=$movie_id";
if (!empty($cat)) {
    $location .= "&cat=" . $cat;
}
$location .= "#recensioni";

Request::redirect($location);
