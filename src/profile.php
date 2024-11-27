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

if (empty($user)) {
    header("Location: index.php");
    exit();
}

// TODO: error handling
if (!$user["is_admin"] || empty($_GET["username"])) {
    $profile_user = $user;
} else {
    $profile_user = $db->get_user($_GET["username"]);
}

$template->replace_vars([
    "username" => $profile_user["username"],
    "name" => $profile_user["name"],
    "last_name" => $profile_user["last_name"],
]);

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
