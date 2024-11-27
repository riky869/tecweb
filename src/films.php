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

if (empty($_GET["cat"])) {
    header("Location: categories.php");
    exit();
}

$category = $_GET["cat"];
$categoryFound = $db->get_category($category);

if (empty($categoryFound)) {
    header("Location: categories.php");
    exit();
}

$template->replace_var("cat_selected", $category);

$movies_data = $db->get_movies_by_category($category);

$template->replace_block_name_arr(
    "movie",
    $movies_data,
    function (Builder $sec, array $i) {
        return $sec->replace_singles(
            [
                "film_id" => $i["id"],
                "film_name" => $i["name"],
                "description" => $i["description"],
            ]
        );
    }
);

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
