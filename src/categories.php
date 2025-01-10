<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DB::from_env();

$categories = $db->get_categories();
$db->close();

$template = Builder::from_template(basename(__FILE__));
$template->replace_block_name_arr("category", $categories, function (Builder $sec, array $i) {
    return $sec->replace_singles([
        "cat_name" => $i["name"],
        "cat_img_class" => "cat_".explode(" ", strtolower($i["name"]))[0], 
    ]);
});

$common = Builder::load_common();
$user = Session::get_user();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
