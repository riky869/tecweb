<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$user = Session::get_user();

if (empty($user)) {
    Request::load_403_page();
}

$db = DB::from_env();

// TODO: error handling
if (!$user["is_admin"] || empty($_GET["username"])) {
    $profile_user = $user;
} else {
    $profile_user = $db->get_user($_GET["username"]);
}

$reviews = $db->get_reviews_by_user($profile_user["username"]);
$db->close();

$template = Builder::from_template(basename(__FILE__));
$template->replace_singles([
    "username" => $profile_user["username"],
    "name" => $profile_user["name"],
    "last_name" => $profile_user["last_name"],
]);

if (!$user["is_admin"] || empty($_GET["username"])) {
    $template->replace_var("user_info", $template->get_block("user_info"), VarType::Block);
    $template->delete_var("admin_user_info", VarType::Block);
} else {
    $template->replace_var("admin_user_info", $template->get_block("admin_user_info")->replace_singles(["breadcrumb_username" => $profile_user["username"]]), VarType::Block);
    $template->delete_var("user_info", VarType::Block);
}

$template->replace_block_name_arr("recensioni", $reviews, function (Builder $t, mixed $i) {
    $t->replace_singles([
        "rec_film_title" => $i["html_name"],
        "rec_film_id" => $i["movie_id"],
        "rec_title" => $i["title"],
        "rec_content" => $i["content"],
        "rec_rating" => $i["rating"],
    ]);
});

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
