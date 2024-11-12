<?php

require_once("utils/db.php");
require_once("utils/request.php");
require_once("utils/check_template.php");
require_once("generators/home.php");
require_once("repositories/movie.php");

Request::allowed_methods(["GET"]);

$db = DbConnection::from_env();
$template = new HomePage;
$movieRepo = new MovieRepo($db);

$movies = $movieRepo->get_movies();

$movieTemplate = new Template('
<div>
    <p>Nome:</p><p>{{name}}</p>
    <p>Descrizione:</p><p>{{description}}</p>
</div>
');

$template->replace_var_array_template("movies", $movieTemplate, $movies, function ($t, $movie) {
    $t->replace_vars([
        "name" => $movie["name"],
        "description" => $movie["description"]
    ]);
});

$content = $template->get_content();
assert_template_render($content);
echo ($content);
