<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");
require_once("utils/input.php");

Request::allowed_methods(["GET", "POST"]);
Session::start();

$user = Session::get_user();

if (empty($user) || !$user["is_admin"]) {
    Request::load_403_page();
}

$checks = [
    'crea_people_name_err' => [
        'err_name' => "nome",
        'optional' => false,
        'checks' => $commonChecks['name']
    ],
    'crea_people_image_err' => [
        'err_name' => "immagine",
        'optional' => true,
        'checks' => $commonChecks['image']
    ],
    'crea_film_name_err' => [
        'err_name' => "nome",
        'optional' => false,
        'checks' => $commonChecks['film']
    ],
    'crea_film_original_name_err' => [
        'err_name' => "nome originale",
        'optional' => true,
        'checks' => $commonChecks['film']
    ],
    'crea_film_original_language_err' => [
        'err_name' => "lingua originale",
        'optional' => true,
        'checks' => $commonChecks['film']
    ],
    'crea_film_release_date_err' => [
        'err_name' => "data di uscita",
        'optional' => true,
        'checks' => [
            [
                'callback' => [Validation::class, 'regexCallback'],
                'args' => ['regex' => "/^\d{4}-\d{2}-\d{2}$/"],
                'error' => 'Inserisci una data nel formato corretto (YYYY-MM-DD).'
            ]
        ]
    ],
    'crea_film_runtime_err' => [
        'err_name' => "durata",
        'optional' => true,
        'checks' => [Validation::minMaxNumCheck(10, 60 * 10)]
    ],
    'crea_film_phase_err' => [
        'err_name' => "fase",
        'optional' => false,
        'checks' => []
    ],
    'crea_film_budget_err' => [
        'err_name' => "budget",
        'optional' => true,
        'checks' => [Validation::minMaxNumCheck(0, -1)]
    ],
    'crea_film_revenue_err' => [
        'err_name' => "revenue",
        'optional' => true,
        'checks' => [Validation::minMaxNumCheck(0, -1)]
    ],
    'crea_film_description_err' => [
        'err_name' => "descrizione",
        'optional' => false,
        'checks' => [Validation::minMaxCharsCheck(8, 1000)]
    ],
    'crea_film_image_err' => [
        'err_name' => "immagine",
        'optional' => true,
        'checks' => $commonChecks['image']
    ],
    'add_people_film_err' => [
        'err_name' => "film",
        'optional' => false,
        'checks' => $commonChecks['film_id']
    ],
    'add_people_people_err' => [
        'err_name' => "persona",
        'optional' => false,
        'checks' => [Validation::minMaxNumCheck(0, -1)]
    ],
    'add_people_role_err' => [
        'err_name' => "ruolo",
        'optional' => false,
        'checks' => [
            [
                'callback' => [Validation::class, 'regexCallback'],
                'args' => ['regex' => "/^[a-zA-ZàèéìòùÀÈÉÌÒÙçÇ\s]+$/"],
                'error' => 'può contenere solo lettere, spazi e caratteri accentati.'
            ]
        ]
    ],
    'add_cat_film_err' => [
        'err_name' => "film",
        'optional' => false,
        'checks' => $commonChecks['film_id']
    ],
    'add_cat_category_err' => [
        'err_name' => "categoria",
        'optional' => false,
        'checks' => [
            [
                'callback' => [Validation::class, 'regexCallback'],
                'args' => ['regex' => "/^[a-zA-ZÀ-ÿ\s-]+$/"],
                'error' => 'può contenere solo lettere, spazi e trattini.'
            ]
        ]
    ],
    'crea_rec_title_err' => [
        'err_name' => "titolo",
        'optional' => false,
        'checks' => $commonChecks['rec_title']
    ],
    'crea_rec_content_err' => [
        'err_name' => "commento",
        'optional' => false,
        'checks' => $commonChecks['rec_content']
    ],
    'crea_rec_rating_err' => [
        'err_name' => "valutazione",
        'optional' => false,
        'checks' => $commonChecks['rec_rating']
    ],
];

$error = [];

$db = DB::from_env();

if (Request::is_post()) {
    $action = $_POST["action"] ?? null;
    if ($action != "create_film" && $action != "create_people" && $action != "add_people_to_film" && $action != "add_category_to_film") {
        Request::load_403_page();
    }

    if ($action == "create_film") {
        if (empty($_POST["name"]) || empty($_POST["phase"]) || empty($_POST["description"])) {
            $error[] = "Compila tutti i campi obbligatori";
        }

        $name = validate($_POST["name"], $checks["crea_film_name_err"], $error);
        $original_name = validate($_POST["original_name"], $checks["crea_film_original_name_err"], $error);
        $original_language = validate($_POST["original_language"], $checks["crea_film_original_language_err"], $error);
        $release_date = validate($_POST["release_date"], $checks["crea_film_release_date_err"], $error);
        $runtime = validate($_POST["runtime"], $checks["crea_film_runtime_err"], $error);
        $phase = validate($_POST["phase"], $checks["crea_film_phase_err"], $error);
        $budget = validate($_POST["budget"], $checks["crea_film_budget_err"], $error);
        $revenue = validate($_POST["revenue"], $checks["crea_film_revenue_err"], $error);
        $description = validate($_POST["description"], $checks["crea_film_description_err"], $error);
        validate($_FILES["image"]["name"], $checks["crea_film_image_err"], $error);
        $image_path = Request::read_upload_file("storage/film/", "image", $error);

        if (empty($error)) {
            try {
                $db->create_film($name, $original_name, $original_language, $release_date, $runtime, $phase, $budget, $revenue, $description, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    // NOTE: impossibile viene usato ID come primary key
                    $error[] = "Film già esistente";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "create_people") {
        if (empty($_POST["name"])) {
            $error[] = "Compila tutti i campi obbligatori";
        }

        validate($_FILES["image"]["name"], $checks["crea_people_image_err"], $error);
        $image_path = Request::read_upload_file("storage/persone/", "image", $error);
        $name = validate($_POST["name"], $checks["crea_people_name_err"], $error);

        if (empty($error)) {
            try {

                $db->create_person($name, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    // NOTE: impossibile viene usato ID come primary key
                    $error[] = "Esiste già una persona con questo nome";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "add_people_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["person_id"]) || empty($_POST["role"]) || empty($_POST["role_type"])) {
            $error[] = "Compila tutti i campi obbligatori";
        }

        $role_type = validate($_POST["role_type"], $checks["add_people_role_err"], $error);
        $movie_id = validate($_POST["film_id"], $checks["add_people_film_err"], $error);
        $person_id = validate($_POST["person_id"], $checks["add_people_people_err"], $error);
        $role = validate($_POST["role"], $checks["add_people_role_err"], $error);

        if ($role_type != "cast" && $role_type != "crew") {
            $error[] = "Tipologia del ruolo non valida";
        }

        if (empty($error)) {
            try {
                switch ($role_type) {
                    case "cast":
                        $db->add_person_to_movie_cast($movie_id, $person_id, $role);
                        break;
                    case "crew":
                        $db->add_person_to_movie_crew($movie_id, $person_id, $role);
                        break;
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error[] = "Questa persona ha già un ruolo nel film";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "add_category_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["category"])) {
            $error[] = "Compila tutti i campi obbligatori";
        }

        $movie_id = validate($_POST["film_id"], $checks["add_cat_film_err"], $error);
        $category = validate($_POST["category"], $checks["add_cat_category_err"], $error);


        if (empty($error)) {
            try {
                $db->add_category_to_movie($movie_id, $category);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error[] = "Questo film ha già questa categoria";
                } else {
                    throw $e;
                }
            }
        }
    }
}

if (Request::is_get()) {
    $template = Builder::from_template(basename(__FILE__));
    $common = Builder::load_common();

    $action = $_SESSION["create_action"] ?? null;
    $error = $_SESSION["create_error"] ?? [];

    unset($_SESSION["create_action"]);
    unset($_SESSION["create_error"]);

    switch ($action) {
        case "create_film":
            $success_text = "Film creato con successo";
            break;
        case "create_people":
            $success_text = "Persona creata con successo";
            break;
        case "add_people_to_film":
            $success_text = "Ruolo aggiunto con successo";
            break;
        case "add_category_to_film":
            $success_text = "Categoria aggiunta con successo";
            break;
    }

    $actions = ["create_film", "create_people", "add_people_to_film", "add_category_to_film"];

    if (empty($error)) {
        $template->delete_blocks(array_map(function (string $i) {
            return $i . "_error";
        }, $actions));
        foreach ($actions as $action_name) {
            $succ_block = $action_name . "_success";
            if ($action_name == $action) {
                $template->replace_var($succ_block, $template->get_block($succ_block)->replace_var("success", $success_text), VarType::Block);
            } else {
                $template->delete_blocks([$succ_block]);
            }
        }
    } else {
        foreach ($actions as $action_name) {
            $err_block = $action_name . "_error";
            if ($action_name == $action) {
                $template->replace_block_name_arr($err_block, $error, function (Builder $t, mixed $i) {
                    $t->replace_var("error", $i);
                });
            } else {
                $template->delete_blocks([$err_block]);
            }
        }
        $template->delete_blocks(array_map(function (string $i) {
            return $i . "_success";
        }, $actions));
    }

    $films = $db->get_movies();
    $persons = $db->get_persons();
    $categories = $db->get_categories();

    $template->replace_block_name_arr("people_option", $persons, function (Builder $t, mixed $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("film_people_option", $films, function (Builder $t, mixed $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("film_cat_option", $films, function (Builder $t, mixed $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("category_option", $categories, function (Builder $t, mixed $i) {
        $t->replace_singles([
            "name" => $i["name"],
        ]);
    });

    $template->build($user, $common);
    $template->delete_secs([]);

    $template->show();
} else if (Request::is_post()) {
    $_SESSION["create_error"] = $error;
    $_SESSION["create_action"] = $action;
    Request::redirect("create.php#$action");
}
