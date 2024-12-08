<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

$user = Session::get_user();

if (!empty($user)) {
    header("Location: profile.php");
    exit();
}

$template = Builder::from_template(basename(__FILE__));

if (Request::is_post()) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $last_name = $_POST["last_name"];

    $db = DB::from_env();
    $created = $db->create_user($username, $password, $name, $last_name);
    if ($created) {
        Session::set_user($db->get_user($username));
        $db->close();
        header("Location: profile.php");
        exit();
    } else {
        $template->replace_var(
            "register_error",
            $template->get_block("register_error")->replace_var("error_value", "Errore creazione utente"),
            VarType::Block
        );
    }
    $db->close();
} else if (Request::is_get()) {
    $template->delete_blocks(["register_error"]);
}

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);
$template->show();
