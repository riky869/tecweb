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
    $new_films = $db->get_incoming_films(7);
    $top_films = $db->get_top_films(7);
    $last_reviews = $db->get_last_reviews(5);
} catch (mysqli_sql_exception $e) {
    throw $e;
}
$db->close();


$template = Builder::from_template(basename(__FILE__));
$common = Builder::load_common();

$template->replace_block_name_arr("new_films", $new_films, function (Builder $t, mixed $i) {
    $t->replace_singles([
        "new_film_title" => $i["name"],
        "new_film_html_title" => $i["html_name"],
        "new_film_id" => $i["id"],
        "new_film_locandina" => !empty($i["image_path"]) ? $i["image_path"] : "images/no_picture_available.png",
        "new_film_alt_img_not_present" => empty($i["image_path"]) ? ", non presente" : "",
        // "new_film_descrizione" => implode(' ', array_slice(explode(' ', $i["description"]), 0, 20)) . ' ...',
    ]);
});

$template->replace_block_name_arr("top_films", $top_films, function (Builder $t, mixed $i) {
    $t->replace_singles([
        "top_film_title" => $i["name"],
        "top_film_html_title" => $i["html_name"],
        "top_film_id" => $i["id"],
        "top_film_locandina" => !empty($i["image_path"]) ? $i["image_path"] : "images/no_picture_available.png",
        "top_film_alt_img_not_present" => empty($i["image_path"]) ? ", non presente" : "",
        // "top_film_descrizione" => implode(' ', array_slice(explode(' ', $i["description"]), 0, 20)) . ' ...',
        "top_film_rating" => number_format($i["avg_rating"], 2),
    ]);
});

$template->replace_block_name_arr("last_reviews", $last_reviews, function (Builder $t, mixed $i) {
    $t->replace_singles([
        "rec_film_title" => $i["html_name"],
        "rec_username" => $i["username"],
        "rec_film_id" => $i["movie_id"],
        "rec_title" => $i["title"],
        "rec_content" => $i["content"],
        "rec_rating" => $i["rating"],
    ]);
});

$template->replace_profile($user, $common);
$template->build($user, $common);
$template->delete_blocks([""]);

$template->show();
