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
$categories = $db->get_categories();

$template->replace_sec_block_arr("category", $categories, function (Builder $sec, array $i) {
    return $sec->replace_var("cat_name", $i["name"]);
});

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
