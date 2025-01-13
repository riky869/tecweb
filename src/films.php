<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$user = Session::get_user();

$category = $_GET["cat"] ?? null;
if (empty($category)) {
    Request::load_404_page();
}
$db = DB::from_env();
$categoryFound = $db->get_category($category);

if (empty($categoryFound)) {
    $db->close();
    Request::load_404_page();
}

$movies_data = $db->get_movies_by_category($category);
$db->close();

$template = Builder::from_template(basename(__FILE__));
$template->replace_var("cat_selected", $category);
$template->replace_block_name_arr(
    "movie",
    $movies_data,
    function (Builder $sec, array $i) use ($category) {
        return $sec->replace_singles(
            [
                "cat_name" => $category,
                "film_id" => $i["id"],
                "film_name" => $i["name"],
                "description" => $i["description"],
            ]
        );
    }
);

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
