<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

if (Session::is_logged()) {
    header("Location: index.php");
}

$login_error = null;
if (Request::is_post()) {
    $db = DB::from_env();

    // fetch user
    $user = $db->get_user_with_password(Request::post_param("username"), Request::post_param("password"));

    if ($user) {
        // Redirect to home
        Session::set_user($user);
        header("location: index.php");
    } else {
        // show error
        $login_error = "Credenziali non valide";
    }
}

$template = Builder::from_template(basename(__FILE__));
$common = Builder::load_common();

$template->build(null, $common);
$template->delete_secs([]);

if ($login_error) {
    $template->replace_var("login_error", $template->get_block("login_error")->replace_var("error", $login_error), VarType::Block);
} else {
    $template->delete_var("login_error", VarType::Block);
}

$template->show();
