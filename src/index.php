<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/session.php");
require_once("utils/builder.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DB::from_env();
$movies = $db->get_movies();
$user = Session::get_user();

$template = Builder::from_template(basename(__FILE__));

$common = Builder::load_common();
$movies = array_map(function ($i) {
    return [
        "name" => $i["name"],
        "description" => $i["description"],
    ];
}, $movies);

$template->replace_secs([
    "header" => $common->get_sec("header"),
    "movie" => $common->get_sec("movie")->replace_vars($movies),
    "footer" => $common->get_sec("footer"),
]);

$template->delete_secs([]);

$template->show();
