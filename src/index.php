<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/session.php");
require_once("utils/builder.php");

Request::allowed_methods(["GET"]);
Session::start();

$user = Session::get_user();


$db = DB::from_env();
try {
    $movies_data = $db->get_movies();
} catch (Exception $e) {
    Request::load_500_page();
}
$last_reviews = $db->get_last_reviews(10);
$db->close();




$template = Builder::from_template(basename(__FILE__));
$common = Builder::load_common();

$template->replace_block_name_arr("last_reviews", $last_reviews, function (Builder $t, array $i) {
    $t->replace_singles([
        "rec_film_title" => $i["name"],
        "rec_film_id" => $i["movie_id"],
        "rec_title" => $i["title"],
        "rec_content" => $i["content"],
        "rec_rating" => $i["rating"],
    ]);
});

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
