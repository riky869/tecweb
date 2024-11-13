<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/check_template.php");
require_once("generators/home.php");
require_once("repositories/movie.php");

Request::allowed_methods(["GET"]);

$db = DbConnection::from_env();
$movieRepo = new MovieRepo($db);
$movies = $movieRepo->get_movies();

$template = new HomePage();
$template->fill_movies($movies);

$template->show();
