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

$categoryFound = !empty($category) ? $db->get_category($category) : null;
$movie = $db->get_movie($movie_id);

if (empty($movie)) {
    Request::load_404_page();
}

$categories = $db->get_movie_categories($movie_id);
$cast = $db->get_movie_cast($movie_id);
$crew = $db->get_movie_crew($movie_id);
$reviews = $db->get_film_reviews($movie_id);

$db->close();

$average_rating = "Non disponibile";
if (!empty($reviews)) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $average_rating = round($total_rating / count($reviews), 2);
}

$template = Builder::from_template(basename(__FILE__));

// TODO: check if movie is in category, maybe creating another db function that join the movie_category with movie

$template->replace_singles([
    "nome_film" => $movie["name"],
    "nome_originale" => $movie["original_name"],
    "lingua_originale" => $movie["original_language"],
    "data_uscita" => str_replace("-", " ", $movie["release_date"]),
    "durata" => $movie["runtime"],
    "stato" => $movie["phase"],
    "budget" => $movie["budget"] > 0 ? $movie["budget"] . ' $' : 'Non disponibile',
    "incassi" => $movie["revenue"] > 0 ? $movie["revenue"] . ' $' : 'Non disponibile',
    "description" => $movie["description"],
    // TODO: da ricontrollare, immagine di default se non presente, rating su che scala
    "locandina" => "images/film/" . $movie["image_path"],
    "valutazione" => $average_rating,
]);


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

$template->replace_block_name_arr("genere", $categories, function (Builder $sec, array $i) {
    $sec->replace_singles([
        "genere" => $i["name"],
        "html_genere" => $i["html_name"],
    ]);
});

$template->replace_block_name_arr("cast", $cast, function (Builder $sec, array $i) {
    $profileImage = $i["profile_image"] ? "images/persone/" . $i["profile_image"] : "images/no_picture_available.png";
    $sec->replace_singles([
        "immagine_cast" => $profileImage,
        "cast_alt_img_not_present" => empty($i["profile_image"]) ? ", non presente" : "",
        "cast_name" => $i["name"],
        "cast_html_name" => $i["html_name"],
        "cast_job" => $i["role"],
    ]);
});

$template->replace_block_name_arr("crew", $crew, function (Builder $sec, array $i) {
    $profileImage = $i["profile_image"] ? "images/persone/" . $i["profile_image"] : "images/no_picture_available.png";
    $sec->replace_singles([
        "immagine_crew" =>  $profileImage,
        "crew_alt_img_not_present" => empty($i["profile_image"]) ? ", non presente" : "",
        "crew_name" => $i["name"],
        "crew_html_name" => $i["html_name"],
        "crew_job" => $i["role"],
    ]);
});

$user = Session::get_user();


if (!empty($user)) {
    $user_review = array_filter($reviews, function ($review, $key) use ($user) {
        return $user["username"] == $review["username"];
    }, ARRAY_FILTER_USE_BOTH);

    if ($user_review) {
        $user_review = array_values($user_review)[0];
        foreach (array_keys($reviews, $user_review, true) as $key) {
            unset($reviews[$key]);
        }
    }
}

$template->replace_block_name_arr("recensioni", $reviews, function (Builder $sec, array $i) use ($category, $movie_id, $user) {
    $sec->replace_singles([
        "rev_username" => $i["username"],
        "rev_title" => $i["title"],
        "rev_content" => $i["content"],
        "rev_rating" => $i["rating"],
    ]);

    if (!empty($user) && $user["is_admin"]) {
        $sec->replace_var("delete_recensione", $sec->get_block("delete_recensione")->replace_singles([
            "rev_username" => $i["username"],
            "film_cat" => $category ?? "",
            "film_id" => $movie_id,
        ]), VarType::Block);
    } else {
        $sec->delete_blocks(["delete_recensione"]);
    }
});


if (empty($user) || !empty($user_review)) {
    $template->delete_blocks(["crea_recensione"]);
} else {
    $template->replace_var("crea_recensione", $template->get_block("crea_recensione")->replace_singles([
        "film_id" => $movie_id,
        "film_cat" => $category ?? "",
    ]), VarType::Block);
}

if (!empty($user) && !empty($user_review)) {
    // se il parametro modifica è 1 ed esiste una review dell'utente loggato mostra il form di modifica
    if (!empty($_GET["modifica"]) && $_GET["modifica"] == 1) {
        $template->replace_var("mod_recensione_utente", $template->get_block("mod_recensione_utente")->replace_singles([
            "film_id" => $movie_id,
            "film_cat" => $category ?? "",
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
            "film_id" => $movie_id,
            "film_cat" => $category ?? "",
        ]), VarType::Block);
        $template->delete_blocks(["mod_recensione_utente"]);
    }
    $template->replace_var("delete_recensione_utente", $template->get_block("delete_recensione_utente")->replace_singles([
        "film_id" => $movie_id,
        "film_cat" => $category ?? "",
    ]), VarType::Block);
}
// altrimenti non mostrare nulla della recensione dell'utente
else {
    $template->delete_blocks(["mod_recensione_utente", "view_recensione_utente", "delete_recensione_utente"]);
}

if (!empty($_SESSION["review_error"])) {
    $template->replace_var(
        "crea_recensione_error",
        $template->get_block("crea_recensione_error")->replace_singles(["crea_recensione_error" => $_SESSION["review_error"]]),
        VarType::Block
    );
    unset($_SESSION["review_error"]);
} else {
    $template->delete_blocks(["crea_recensione_error"]);
}

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
