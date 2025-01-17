<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

$user = Session::get_user();

if (!empty($user)) {
    Request::load_403_page();
}

$template = Builder::from_template(basename(__FILE__));

$register_error = null;

if (Request::is_post()) {
    $username = $_POST["username"] ?? null;
    $password = $_POST["password"] ?? null;
    $name = $_POST["name"] ?? null;
    $last_name = $_POST["last_name"] ?? null;


    if (empty($username) || empty($password) || empty($name) || empty($last_name)) {
        $register_error = "Compilare tutti i campi";
    } else if (strlen($username) < 4 || strlen($username) > 20) {
        $register_error = "L'username deve essere lungo tra 4 e 20 caratteri";
    } else if (strlen($password) < 4) {
        $register_error = "La Password deve essere più lunga di 4 caratteri";
    } else if (strlen($name) < 2) {
        $register_error = "Nome deve essere lungo almeno 2 caratteri";
    } else if (strlen($last_name) < 2) {
        $register_error = "Cognome deve essere lungo almeno 2 caratteri";
    } else {
        $db = DB::from_env();
        try {
            $created = $db->create_user($username, $password, $name, $last_name);
            if ($created) {
                Session::set_user($db->get_user($username));
                $db->close();
                Request::redirect("profile.php");
            } else {
                $register_error = "Errore durante la creazione dell'utente";
            }
            $db->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                $register_error = "Username già esistente";
            } else {
                $register_error = "Errore interno del <span lang=\"en\">server</span>";
            }
        }
    }

    if (!empty($register_error)) {
        $_SESSION["register_error"] = $register_error;
    }

    Request::redirect("signup.php");
}

if (Request::is_get()) {
    $register_error = $_SESSION["register_error"] ?? null;
    unset($_SESSION["register_error"]);

    if (empty($register_error)) {
        $template->delete_blocks(["register_error"]);
    } else {
        $template->replace_var(
            "register_error",
            $template->get_block("register_error")->replace_var("register_error", $register_error),
            VarType::Block
        );
    }

    $common = Builder::load_common();
    $template->build($user, $common);
    $template->delete_secs([]);
    $template->show();
}
