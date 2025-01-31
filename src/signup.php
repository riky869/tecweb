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

$template = Builder::from_template(basename(__FILE__));

$error = [];
$checks = [
    'signup_username_err' => [
        'err_name' => toSpan("username", "en"),
        'optional' => false,
        'checks' => [
            [
                'callback' => [Validation::class, 'regexCallback'],
                'args' => ['regex' => "/^[a-zA-Z0-9_]{3,15}$/"],
                'error' => 'Deve essere lungo tra 3 e 15 caratteri, può contenere lettere, numeri e underscore.'
            ]
        ]
    ],
    'signup_password_err' => [
        'err_name' => toSpan("password", "en"),
        'optional' => false,
        'checks' => [
            [
                'callback' => [Validation::class, 'regexCallback'],
                'args' => ['regex' => "/^(?=.*[A-Za-z])(?=.*\d).{8,}$/"],
                'error' => 'Deve essere lunga almeno 8 caratteri e contenere almeno una lettera e un numero.'
            ]
        ]
    ],
    'signup_name_err' => [
        'err_name' => "nome",
        'optional' => false,
        'checks' => $commonChecks['name']
    ],
    'signup_last_name_err' => [
        'err_name' => "cognome",
        'optional' => false,
        'checks' => $commonChecks['name']
    ],
];

if (Request::is_post()) {
    $username = $_POST["username"] ?? null;
    $password = $_POST["password"] ?? null;
    $name = $_POST["name"] ?? null;
    $last_name = $_POST["last_name"] ?? null;

    if (empty($username) || empty($password) || empty($name) || empty($last_name)) {
        $error[] = "Compilare tutti i campi";
    }

    $username = validate($username, $checks["signup_username_err"], $error);
    $password = validate($password, $checks["signup_password_err"], $error);
    $name = validate($name, $checks["signup_name_err"], $error);
    $last_name = validate($last_name, $checks["signup_last_name_err"], $error);


    if (empty($error)) {
        $db = DB::from_env();
        try {
            $created = $db->create_user($username, $password, $name, $last_name);
            if ($created) {
                Session::set_user($db->get_user($username));
                $db->close();
                Request::redirect("profile.php");
            } else {
                $error[] = "Errore durante la creazione dell'utente";
            }
            $db->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                $error[] = "Username già esistente";
            } else throw $e;
        }
    }

    if (!empty($error)) {
        $_SESSION["register_error"] = $error;
    }

    Request::redirect("signup.php");
} else if (Request::is_get()) {
    $error = $_SESSION["register_error"] ?? [];
    unset($_SESSION["register_error"]);

    if (empty($error)) {
        $template->delete_blocks(["register_error"]);
    } else {
        $template->replace_block_name_arr("register_error", $error, function (Builder $t, mixed $i) {
            $t->replace_var("register_error", $i);
        });
    }

    $common = Builder::load_common();
    $template->replace_profile(null, $common);
    $template->build(null, $common);
    $template->delete_secs([]);
    $template->show();
}
