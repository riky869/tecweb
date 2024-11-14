<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/home.php");
require_once("repositories/movie.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$movieRepo = new MovieRepo($db);
$movies = $movieRepo->get_movies();
$user = Session::get_user();

$template = new HomePage($user);
$template->fill_movies($movies);

$template->replace_var("breadcrumb", "<p>Ti trovi in: Home</p>");

$template->show();
