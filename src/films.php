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

$template->replace_secs([
    "header" => $common->get_sec("header"),
    "footer" => $common->get_sec("footer"),
]);

$template->delete_secs([]);

$template->show();
