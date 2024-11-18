<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();


if (!$user["is_admin"]) {
    header("Location: index.php");
}

$db = DB::from_env();
$user = Session::get_user();
$template = Builder::from_template(__FILE__);


$template->delete_vars(["main"]);

$template->show();
