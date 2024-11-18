<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$user = Session::get_user();
$template = Builder::from_template("film");

$categories_url = MenuItem::CATEGORIES->get_menu()["url"];
$category_url = MenuItem::FILMS->get_menu()["url"] . "?cat=Horror";
$category_name = "Horror";
$film_name = "Odissea Nello Spazio";
$breadcrumb = "Home >> ";
$breadcrumb .= "<a href=\"$categories_url\">Categorie</a> >> ";
$breadcrumb .= "<a href=\"$category_url\">$category_name</a> >> ";
$breadcrumb .= $film_name;

$template->replace_var("breadcrumb", $breadcrumb);

$template->delete_vars(["main"]);

$template->show();
