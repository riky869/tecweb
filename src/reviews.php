<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/reviews.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$template = new ReviewsPage();

$user = Session::get_user();
$template->fill_profile($user);

$template->delete_vars(["main"]);

$template->show();
