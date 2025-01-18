<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");
require_once("utils/input.php");

Request::allowed_methods(["POST"]);
Session::start();

$user = Session::get_user();

if (empty($user) || !$user["is_admin"]) {
    Request::load_403_page();
}

$action = $_POST["type"] ?? null;
if ($action != "crew" && $action != "cast" && $action != "category") {
    Request::load_403_page();
}

if (empty($_POST["film_id"])) {
    Request::load_404_page();
}

$film_id = pulisciInput($_POST["film_id"]);
$cat = pulisciInput($_POST["cat"] ?? "");
$category = pulisciInput($_POST["film_cat"] ?? "");

$db = DB::from_env();

if ($action == "crew" || $action == "cast") {
    if (empty($_POST["person_id"])) {
        Request::load_403_page();
    }

    $person_id = pulisciInput($_POST["person_id"]);

    try {
        switch ($action) {
            case "crew":
                $res = $db->delete_person_from_movie_crew($film_id, (int)($person_id));
                break;
            case "cast":
                $res = $db->delete_person_from_movie_cast($film_id, (int)$person_id);
                break;
        }
    } catch (mysqli_sql_exception $e) {
        throw $e;
    }
} else if ($action == "category") {
    if (empty($_POST["genere"])) {
        Request::load_403_page();
    }
    $genere = pulisciInput($_POST["genere"]);

    try {
        $db->delete_category_from_movie($film_id, $genere);
    } catch (mysqli_sql_exception $e) {
        throw $e;
    }
}


$location = "film.php?id=$film_id";
if (!empty($cat)) {
    $location .= "&cat=" . $cat;
}
switch ($action) {
    case "crew":
        $location .= "#crew";
        break;
    case "cast":
        $location .= "#cast";
        break;
    case "category":
        $location .= "#generi";
        break;
}

Request::redirect($location);
