<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("repositories/user.php");
require_once("generators/login.php");
require_once("utils/session.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

$login_error = null;

if (Request::is_post()) {
    $db = DB::from_env();

    // fetch user
    $user = $db->get_user_by_password(Request::post_param("username"), Request::post_param("password"));

    if ($user) {
        // Redirect to home
        Session::set_user($user);
        header("location: index.php");
    } else {
        // show error
        $login_error = "<p>Credenziali non valide</p>";
    }
} else if (Request::is_get()) {
    if (Session::is_logged()) {
        header("Location: index.php");
    }
}

$template = Builder::from_template(basename(__FILE__));

if ($login_error) {
    $template->replace_var("login_error", $login_error);
} else {
    $template->delete_var("login_error");
}

$template->show();
