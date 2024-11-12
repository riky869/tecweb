<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("repositories/user.php");
require_once("utils/check_template.php");
require_once("generators/login.php");

Request::allowed_methods(["GET", "POST"]);

$login_error = "";

if (Request::is_post()) {
    $db = DbConnection::from_env();
    $repo = new UserRepo($db);

    // fetch user
    $user = $repo->get_user_by_password(Request::post_param("username"), Request::post_param("password"));

    if (!empty($user)) {
        // Redirect to home
        $content = "";
    } else {
        // show error
        $login_error = "<p>Credenziali non valide</p>";
    }
} else if (Request::is_get()) {
}

$template = new LoginPage();
$template->fill_login();

if ($login_error) {
    $template->replace_var("login_error", $login_error);
} else {
    $template->delete_var("login_error");
}

$template->show();
