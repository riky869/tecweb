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
        if (empty($_POST["name"]) || empty($_POST["original_name"]) || empty($_POST["original_language"]) || empty($_POST["runtime"]) || empty($_POST["phase"]) || empty($_POST["budget"]) || empty($_POST["revenue"]) || empty($_POST["description"])) {
            $error = "Compila tutti i campi obbligatori";
        }

        $name = pulisciInput($_POST["name"]);
        $original_name = pulisciInput($_POST["original_name"]);
        $original_language = pulisciInput($_POST["original_language"]);
        $release_date = !empty($_POST["release_date"]) ? pulisciInput($_POST["release_date"]) : null;
        $runtime = pulisciInput($_POST["runtime"]);
        $phase = pulisciInput($_POST["phase"]);
        $budget = pulisciInput($_POST["budget"]);
        $revenue = pulisciInput($_POST["revenue"]);
        $description = pulisciInput($_POST["description"]);

        $image_path = Request::read_upload_file("storage/film/", "image", $error);

        if (empty($error)) {
            try {
                $db->create_film($name, $original_name, $original_language, $release_date, $runtime, $phase, $budget, $revenue, $description, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error = "Film già esistente";
                } else {
                    Request::load_500_page();
                }
            }
        }
    } else if ($action == "create_people") {
        if (empty($_POST["name"])) {
            $error = "Compila tutti i campi obbligatori";
        }

        $image_path = Request::read_upload_file("storage/persone/", "image", $error);
        $name = pulisciInput($_POST["name"]);

        if (empty($error)) {
            try {

                $db->create_person($name, $image_path);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error = "Persona già presente";
                } else {
                    Request::load_500_page();
                }
            }
        }
    } else if ($action == "add_people_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["person_id"]) || empty($_POST["role"]) || empty($_POST["role_type"])) {
            $error = "Compila tutti i campi obbligatori";
        }

        $role_type = pulisciInput($_POST["role_type"]);
        $movie_id = pulisciInput($_POST["film_id"]);
        $person_id = pulisciInput($_POST["person_id"]);
        $role = pulisciInput($_POST["role"]);

        if ($role_type != "cast" && $role_type != "crew") {
            $error = "Tipologia del ruolo non valido";
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
                    $error = "Ruolo già presente per questo film";
                } else {
                    Request::load_500_page();
                }
            }
        }
    } else if ($action == "add_category_to_film") {
        if (empty($_POST["film_id"]) || empty($_POST["category"])) {
            $error = "Compila tutti i campi obbligatori";
        }

        $movie_id = pulisciInput($_POST["film_id"]);
        $category = pulisciInput($_POST["category"]);

        if (empty($error)) {
            try {
                $db->add_category_to_movie($movie_id, $category);
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $error = "Categoria già presente per questo film";
                } else {
                    Request::load_500_page();
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
            $success_text = "Film aggiunto con successo";
            break;
        case "create_people":
            $success_text = "Persona aggiunta con successo";
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
