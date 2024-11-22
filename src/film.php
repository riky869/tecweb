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

$movie_id = $_GET["id"];

if (empty($_GET["id"])) {
    header("Location: categories.php");
    exit();
}

$movie = $db->get_movie($movie_id);

$template->replace_vars([
    "nome_film" => $movie["name"],
    "nome_cat" => $movie["category"],
    "name" => $movie["name"],
    "description" => $movie["description"],
]);

$template->build($user, $common);

$template->show();
