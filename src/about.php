<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

// $db = DB::from_env();
// $db->close();

$user = Session::get_user();
$template = Builder::from_template(basename(__FILE__));

$common = Builder::load_common();

$template->build($user, $common);
$template->delete_secs([]);

$template->show();
