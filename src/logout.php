<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/session.php");

Request::allowed_methods(["POST"]);
Session::start();

Session::logout();
// Redirect to home
header("location: index.php");
