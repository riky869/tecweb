<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("repositories/user.php");
require_once("utils/check_template.php");
require_once("generators/login.php");

Request::allowed_methods(["GET", "POST"]);

$template = new LoginPage();

if (Request::is_post()) {
    $db = DbConnection::from_env();
    $repo = new UserRepo($db);

    // fetch user
    $user = $repo->get_user_by_password(Request::post_param("username"), Request::post_param("password"));

    if (!empty($user)) {
        $content = "";
    } else {
    }
} else if (Request::is_get()) {
}

$content = $template->get_content();
assert_template_render($content);
echo ($content);
