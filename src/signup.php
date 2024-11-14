<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/page.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

$db = DbConnection::from_env();

$template = new BasePage(MenuItem::SIGNUP, null);

$template->delete_vars(["main"]);

$template->show();
