<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/categories.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$user = Session::get_user();
$template = new CategoriesPage($user);

$template->delete_vars(["main"]);

$template->show();
