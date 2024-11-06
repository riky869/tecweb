<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("generators/login.php");

$template = new LoginPage();

$content = "";

if (Request::is_post()) {
    $db = DbConnection::from_env();
    $conn = $db->get_conn();

    // fetch user
    $user = null;

    if (!empty($user)) {
        $content = "";
    } else {
    }
} else if (Request::is_get()) {
    $content = $template->get_content();
}

echo ($content);
