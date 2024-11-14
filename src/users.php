<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/page.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$user = Session::get_user();

if (!$user["is_admin"]) {
    header("Location: index.php");
}

$template = new BasePage(MenuItem::USERS, $user);

$template->delete_vars(["main"]);

$template->show();
