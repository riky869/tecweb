<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$user = Session::get_user();

if (empty($user) || !$user["is_admin"]) {
    header("Location: index.php");
    exit();
}

$common = Builder::load_common();

$db = DB::from_env();
$users = $db->get_users();
$db->close();
$template = Builder::from_template(basename(__FILE__));
$template->replace_block_name_arr("users_list", $users, function (Builder $sec, array $i) {
    $sec->replace_singles(["profile_username" => $i["username"]]);
});

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
