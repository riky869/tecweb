<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/session.php");
require_once("utils/builder.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DB::from_env();
$movies = $db->get_movies();
$user = Session::get_user();

$template = Builder::from_template("index");

$template->delete_secs(["movies"]);

$template->show();
