<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/builder.php");
require_once("utils/session.php");

Request::allowed_methods(["GET"]);
Session::start();

if (empty($_GET["id"])) {
    Request::load_404_page();
}

$movie_id = $_GET["id"];
$category = $_GET["cat"] ?? "";

$db = DB::from_env();
$user = Session::get_user();

$movie = $db->get_movie($movie_id);

if (empty($movie)) {
    Request::load_404_page();
}

$categoryFound = !empty($category) ? $db->get_category($category) : null;
$categories = $db->get_movie_categories($movie_id);
$cast = $db->get_movie_cast($movie_id);
$crew = $db->get_movie_crew($movie_id);
$reviews = $db->get_film_reviews($movie_id);

$db->close();

if (!empty($categoryFound)) {
    $categoryFound = array_filter($categories, function ($cat, $_) use ($category) {
        return $cat["name"] == $category;
    }, ARRAY_FILTER_USE_BOTH);
    $categoryFound = !empty($categoryFound) ? array_values($categoryFound)[0] : null;
}

$average_rating = null;
if (!empty($reviews)) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $average_rating = round($total_rating / count($reviews), 2);
}

$template = Builder::from_template(basename(__FILE__));

$template->replace_singles([
    "nome_film" => $movie["name"],
    "nome_originale" => !empty($movie["original_name"]) ? $movie["original_name"] : "Non disponibile",
    "lingua_originale" => !empty($movie["original_language"]) ? $movie["original_language"] : "Non disponibile",
    "stato" => $movie["phase"],
    "budget" => !empty($movie["budget"]) && $movie["budget"] > 0 ? number_format($movie["budget"], 0, ',', '.') . ' $' : 'Non disponibile',
    "incassi" =>  !empty($movie["revenue"]) && $movie["revenue"] > 0 ? number_format($movie["revenue"], 0, ',', '.') . ' $' : 'Non disponibile',
    "description" => $movie["description"],
    "locandina" => !empty($movie["image_path"]) ? $movie["image_path"] : "images/no_picture_available.png",
    "locandina_alt_img_not_present" => empty($movie["image_path"]) ? ", non presente" : "",
    "film_id" => $movie_id,
    "film_cat" => !empty($categoryFound) ? $categoryFound["name"] : "",
]);

if (!empty($movie["release_date"])) {
    $template->replace_var("data_release_presente", $template->get_block("data_release_presente")->replace_singles([
        "data_uscita_iso" => $movie["release_date"],
        "data_uscita" =>  str_replace("-", "-", $movie["release_date"]),
    ]), VarType::Block);
    $template->delete_blocks(["data_release_non_presente"]);
} else {
    $template->show_block("data_release_non_presente");
    $template->delete_blocks(["data_release_presente"]);
}

if (!empty($movie["runtime"])) {
    $template->replace_var("durata_presente", $template->get_block("durata_presente")->replace_var("durata", $movie["runtime"]), VarType::Block);
    $template->delete_blocks(["durata_non_presente"]);
} else {
    $template->show_block("durata_non_presente");
    $template->delete_blocks(["durata_presente"]);
}

if (!empty($average_rating)) {
    $template->replace_var("valutazione_presente", $template->get_block("valutazione_presente")->replace_var("valutazione", $average_rating), VarType::Block);
    $template->delete_blocks(["valutazione_non_presente"]);
} else {
    $template->show_block("valutazione_non_presente");
    $template->delete_blocks(["valutazione_presente"]);
}

$delete_forms = ["delete_crew", "delete_cast", "delete_genere", "delete_film"];
if (!empty($user) && $user["is_admin"]) {
    foreach ($delete_forms as $form) {
        $template->replace_var($form, $template->get_block($form), VarType::Block);
    }
} else {
    $template->delete_blocks($delete_forms);
}

if (empty($categoryFound)) {
    $template->replace_var("breadcrumb_da_altro", $template->get_block("breadcrumb_da_altro")->replace_singles([
        "breadcrumb_nome_film" => $movie["name"],
    ]), VarType::Block);
    $template->delete_var("breadcrumb_da_categoria", VarType::Block);
} else {
    $template->replace_var("breadcrumb_da_categoria", $template->get_block("breadcrumb_da_categoria")->replace_singles([
        "breadcrumb_nome_cat" => $categoryFound["name"],
        "breadcrumb_nome_film" => $movie["name"],
    ]), VarType::Block);
    $template->delete_var("breadcrumb_da_altro", VarType::Block);
}

if (!empty($categories)) {
    $template->replace_var("show_generi", $template->get_block("show_generi")->replace_block_name_arr("genere", $categories, function (Builder $sec, array $i) {
        $sec->replace_singles([
            "genere" => $i["name"],
            "html_genere" => $i["html_name"],
        ]);
    }), VarType::Block);
    $template->delete_blocks(["show_no_generi"]);
} else {
    $template->show_block("show_no_generi");
    $template->delete_blocks(["show_generi"]);
}


if (!empty($cast)) {
    $template->replace_var("show_cast", $template->get_block("show_cast")->replace_block_name_arr("cast", $cast, function (Builder $sec, array $i) {
        $profileImage = $i["profile_image"] ? $i["profile_image"] : "images/no_picture_available.png";
        $sec->replace_singles([
            "immagine_cast" => $profileImage,
            "cast_alt_img_not_present" => empty($i["profile_image"]) ? ", non presente" : "",
            "cast_name" => $i["name"],
            "cast_html_name" => $i["html_name"],
            "cast_job" => $i["role"],
            "cast_id" => $i["person_id"],
        ]);
    }), VarType::Block);
    $template->delete_blocks(["show_no_cast"]);
} else {
    $template->show_block("show_no_cast");
    $template->delete_blocks(["show_cast"]);
}

if (!empty($crew)) {
    $template->replace_var("show_crew", $template->get_block("show_crew")->replace_block_name_arr("crew", $crew, function (Builder $sec, array $i) {
        $profileImage = $i["profile_image"] ? $i["profile_image"] : "images/no_picture_available.png";
        $sec->replace_singles([
            "immagine_crew" =>  $profileImage,
            "crew_alt_img_not_present" => empty($i["profile_image"]) ? ", non presente" : "",
            "crew_name" => $i["name"],
            "crew_html_name" => $i["html_name"],
            "crew_job" => $i["role"],
            "crew_id" => $i["person_id"],
        ]);
    }), VarType::Block);
    $template->delete_blocks(["show_no_crew"]);
} else {
    $template->show_block("show_no_crew");
    $template->delete_blocks(["show_crew"]);
}


if (!empty($user)) {
    $user_review = array_filter($reviews, function ($review, $_) use ($user) {
        return $user["username"] == $review["username"];
    }, ARRAY_FILTER_USE_BOTH);

    if ($user_review) {
        $user_review = array_values($user_review)[0];
        foreach (array_keys($reviews, $user_review, true) as $key) {
            unset($reviews[$key]);
        }
    }
}

if (!empty($reviews) || !empty($user_review)) {
    $template->replace_block_name_arr("recensioni", $reviews, function (Builder $sec, array $i) use ($user) {
        $sec->replace_singles([
            "rev_username" => $i["username"],
            "rev_title" => $i["title"],
            "rev_content" => $i["content"],
            "rev_rating" => $i["rating"],
        ]);

        if (!empty($user) && $user["is_admin"]) {
            $sec->replace_var("delete_recensione", $sec->get_block("delete_recensione")->replace_singles([
                "rev_username" => $i["username"],
            ]), VarType::Block);
        } else {
            $sec->delete_blocks(["delete_recensione"]);
        }
    });
    $template->delete_blocks(["no_recensioni"]);
} else {
    $template->show_block("no_recensioni");
    $template->delete_blocks(["recensioni"]);
}

if (empty($user) || !empty($user_review)) {
    $template->delete_blocks(["crea_recensione"]);
} else {
    $template->replace_var("crea_recensione", $template->get_block("crea_recensione"), VarType::Block);
}

// show error under submit buttons
if (!empty($_SESSION["review_error"])) {
    $error = $_SESSION["review_error"];
    unset($_SESSION["review_error"]);

    if (!empty($_GET["modifica"]) && $_GET["modifica"] == 1) {
        $template->replace_block_name_arr("mod_recensione_error", $error, function (Builder $t, mixed $i) {
            $t->replace_var("error", $i);
        });
        $template->delete_blocks(["crea_recensione_error"]);
    } else {
        $template->replace_block_name_arr("crea_recensione_error", $error, function (Builder $t, mixed $i) {
            $t->replace_var("error", $i);
        });
        $template->delete_blocks(["mod_recensione_error"]);
    }
} else {
    $template->delete_blocks(["crea_recensione_error", "mod_recensione_error"]);
}

if (!empty($user) && !empty($user_review)) {
    // se il parametro modifica è 1 ed esiste una review dell'utente loggato mostra il form di modifica
    if (!empty($_GET["modifica"]) && $_GET["modifica"] == 1) {
        $template->replace_var("mod_recensione_utente", $template->get_block("mod_recensione_utente")->replace_singles([
            "mod_title" => $user_review["title"],
            "mod_content" => $user_review["content"],
            "mod_rating" => $user_review["rating"],
        ]), VarType::Block);
        $template->delete_blocks(["view_recensione_utente"]);
    }
    // se esiste una review dell'utente loggato ma il parametro non è 1 mostra solo in readonly
    else {
        $template->replace_var("view_recensione_utente", $template->get_block("view_recensione_utente")->replace_singles([
            "user_rev_rating" => $user_review["rating"],
            "user_rev_title" => $user_review["title"],
            "user_rev_content" => $user_review["content"],
        ]), VarType::Block);
        $template->delete_blocks(["mod_recensione_utente"]);
    }
    $template->replace_var("delete_recensione_utente", $template->get_block("delete_recensione_utente"), VarType::Block);
}
// altrimenti non mostrare nulla della recensione dell'utente
else {
    $template->delete_blocks(["mod_recensione_utente", "view_recensione_utente", "delete_recensione_utente"]);
}

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
