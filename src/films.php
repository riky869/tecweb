<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/page.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();
$user = Session::get_user();
$template = new BasePage(MenuItem::FILM, $user);


$categories_url = MenuItem::CATEGORIES->get_menu()["url"];
$category_name = "Horror";
$breadcrumb = "Home >> ";
$breadcrumb .= "<a href=\"$categories_url\">Categorie</a> >> ";
$breadcrumb .= $category_name;

$template->replace_var("breadcrumb", $breadcrumb);

$template->delete_vars(["main"]);

$template->show();
