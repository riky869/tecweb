<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");
require_once("utils/input.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

if (Session::is_logged()) {
    Request::redirect("index.php");
}

$error = [];
$checks = [
    'login_username_err' => [
        'err_name' => toSpan("username", "en"),
        'optional' => false,
        'checks' => [Validation::minMaxCharsCheck(4, -1)]
    ],
    'login_password_err' => [
        'err_name' => toSpan("password", "en"),
        'optional' => false,
        'checks' => [Validation::minMaxCharsCheck(4, -1)]
    ],
];

if (Request::is_post()) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $error[] = "Inserisci tutti i campi obbligatori";
    }

    $username = validate($_POST["username"], $checks["login_username_err"], $error);
    $password = validate($_POST["password"], $checks["login_password_err"], $error);

    if (empty($error)) {
        $db = DB::from_env();
        // fetch user
        $user = $db->get_user_with_password($username, $password);
        $db->close();

        if (!empty($user)) {
            // Redirect to home
            Session::set_user($user);
            Request::redirect("index.php");
        } else {
            // show error
            $error[] = "<span lang=\"en\">Username</span> o <span lang=\"en\">password</span> errate";
        }
    }

    if (!empty($error)) {
        $_SESSION["login_error"] = $error;
    }
    Request::redirect("login.php");
}

if (Request::is_get()) {
    $error = $_SESSION["login_error"] ?? [];
    unset($_SESSION["login_error"]);

    $template = Builder::from_template(basename(__FILE__));
    $common = Builder::load_common();

    $template->build(null, $common);
    $template->delete_secs([]);

    if (!empty($error)) {
        $template->replace_block_name_arr("login_error", $error, function (Builder $t, mixed $i) {
            $t->replace_var("login_error", $i);
        });
    } else {
        $template->delete_var("login_error", VarType::Block);
    }

    $template->show();
}
