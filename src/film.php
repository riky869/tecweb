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
$category = $_GET["cat"] ?? null;

$db = DB::from_env();

$categoryFound = $category ? $db->get_category($category) : null;
$movie = $db->get_movie($movie_id);

if (empty($movie)) {
    Request::load_404_page();
}

$categories = $db->get_movie_categories($movie_id);
$cast = $db->get_movie_cast($movie_id);
$crew = $db->get_movie_crew($movie_id);
$reviews = $db->get_film_reviews($movie_id);

$db->close();

$average_rating = 0;
if (!empty($reviews)) {
    $total_rating = array_sum(array_column($reviews, 'rating'));
    $average_rating = $total_rating / count($reviews);
}

$template = Builder::from_template(basename(__FILE__));

// TODO: check if movie is in category, maybe creating another db function that join the movie_category with movie

$template->replace_singles([
    "nome_film" => $movie["name"],
    "nome_originale" => $movie["original_name"],
    "lingua_originale" => $movie["original_language"],
    "data_uscita" => $movie["release_date"],
    "durata" => $movie["runtime"],
    "stato" => $movie["phase"],
    "budget" => $movie["budget"],
    "incassi" => $movie["revenue"],
    "description" => $movie["description"],
    // TODO: da ricontrollare, immagine di default se non presente, rating su che scala
    "locandina" => "./images/film/" . $movie["image_path"],
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
        "genere" => $i["category_name"],
    ]);
});

$template->replace_block_name_arr("cast", $cast, function (Builder $sec, array $i) {
    $sec->replace_singles([
        "immagine_cast" => "./images/persone/" . $i["profile_image"],
        "cast_name" => $i["name"],
        "cast_job" => $i["role"],
    ]);
});

$template->replace_block_name_arr("crew", $crew, function (Builder $sec, array $i) {
    $sec->replace_singles([
        "immagine_crew" => "./images/persone/" . $i["profile_image"],
        "crew_name" => $i["name"],
        "crew_job" => $i["role"],
    ]);
});

$template->replace_block_name_arr("recensioni", $reviews, function (Builder $sec, array $i) {
    $sec->replace_singles([
        "rev_username" => $i["username"],
        "rev_title" => $i["title"],
        "rev_content" => $i["content"],
        "rev_rating" => $i["rating"],
    ]);
});

$user = Session::get_user();

$user_review = array_filter($reviews, function ($review) {
    return $user["username"] ?? null === $review["username"];
})[0] ?? null;


if (empty($user)) {
    $template->delete_blocks(["crea_recensione"]);
} else {
    $template->replace_var("crea_recensione", $template->get_block("crea_recensione")->replace_singles([
        "film_id" => $movie_id,
    ]), VarType::Block);
}

$common = Builder::load_common();
$template->build($user, $common);
$template->delete_secs([]);

$template->show();
