<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DB::from_env();
$user = Session::get_user();
$template = Builder::from_template(basename(__FILE__));
$common = Builder::load_common();


if (empty($_GET["id"])) {
    header("Location: categories.php");
    exit();
}

$movie_id = $_GET["id"];
$movie = $db->get_movie($movie_id);

if (empty($movie)) {
    header("Location: categories.php");
    exit();
}

$template->replace_vars([
    "nome_film" => $movie["name"],
    "nome_cat" => $movie["category"],
    "description" => $movie["description"],
]);

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
