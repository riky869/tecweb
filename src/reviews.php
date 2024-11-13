<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/reviews.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$template = new ReviewsPage();

if (Session::is_logged()) {
    $user = Session::get_user();
    $template->fill_profile($user);
} else {
    $template->delete_var("profile");
}

$template->delete_vars(["main"]);

$template->show();
