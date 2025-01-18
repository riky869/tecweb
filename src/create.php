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

$error = null;

$db = DB::from_env();

if (Request::is_post()) {
    $action = $_POST["action"] ?? null;
    if ($action != "create_film" && $action != "create_people" && $action != "add_people_to_film" && $action != "add_category_to_film") {
        Request::load_403_page();
    }

    if ($action == "create_film") {
        if (empty($_POST["name"]) || empty($_POST["phase"]) || empty($_POST["description"])) {
            $error = "Compila tutti i campi obbligatori";
        }

        $name = clean_input($_POST["name"]);
        $original_name = !empty($_POST["original_name"]) ? clean_input($_POST["original_name"]) : null;
        $original_language = !empty($_POST["original_language"]) ? clean_input($_POST["original_language"]) : null;
        $release_date = !empty($_POST["release_date"]) ? clean_input($_POST["release_date"]) : null;
        $runtime = !empty($_POST["runtime"]) ? clean_input($_POST["runtime"]) : null;
        $phase = clean_input($_POST["phase"]);
        $budget = !empty($_POST["budget"]) ? clean_input($_POST["budget"]) : null;
        $revenue = !empty($_POST["revenue"]) ? clean_input($_POST["revenue"]) : null;
        $description = clean_input($_POST["description"]);

        $image_path = Request::read_upload_file("storage/film/", "image", $error);

        if (empty($error)) {
            try {
                $db->create_film($name, $original_name, $original_language, $release_date, $runtime, $phase, $budget, $revenue, $description, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    // NOTE: impossibile viene usato ID come primary key
                    $error = "Film già esistente";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "create_people") {
        if (empty($_POST["name"])) {
            $error = "Compila tutti i campi obbligatori";
        } else {
            $image_path = Request::read_upload_file("storage/persone/", "image", $error);
            $name = clean_input($_POST["name"]);
        }

        if (empty($error)) {
            try {

                $db->create_person($name, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    // NOTE: impossibile viene usato ID come primary key
                    $error = "Esiste già una persona con questo nome";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "add_people_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["person_id"]) || empty($_POST["role"]) || empty($_POST["role_type"])) {
            $error = "Compila tutti i campi obbligatori";
        } else {
            $role_type = clean_input($_POST["role_type"]);
            $movie_id = clean_input($_POST["film_id"]);
            $person_id = clean_input($_POST["person_id"]);
            $role = clean_input($_POST["role"]);

            if ($role_type != "cast" && $role_type != "crew") {
                $error = "Tipologia del ruolo non valido";
            }
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
                    $error = "Questa persona ha già questo ruolo nel film";
                } else {
                    throw $e;
                }
            }
        }
    } else if ($action == "add_category_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["category"])) {
            $error = "Compila tutti i campi obbligatori";
        } else {
            $movie_id = clean_input($_POST["film_id"]);
            $category = clean_input($_POST["category"]);
        }

        if (empty($error)) {
            try {
                $db->add_category_to_movie($movie_id, $category);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error = "Questo film ha già questa categoria";
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
    $error = $_SESSION["create_error"] ?? null;

    unset($_SESSION["create_action"]);
    unset($_SESSION["create_error"]);

    switch ($action) {
        case "create_film":
            $success_text = "Film creato con successo";
            break;
        case "create_people":
            $success_text = "Persona create con successo";
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
                $template->replace_var($err_block, $template->get_block($err_block)->replace_var("error", $error), VarType::Block);
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

    $template->replace_block_name_arr("people_option", $persons, function (Builder $t, array $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("film_people_option", $films, function (Builder $t, array $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("film_cat_option", $films, function (Builder $t, array $i) {
        $t->replace_singles([
            "id" => $i["id"],
            "name" => $i["name"]
        ]);
    });

    $template->replace_block_name_arr("category_option", $categories, function (Builder $t, array $i) {
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
