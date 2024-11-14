<?php

require_once("utils/checks.php");
require_once("generators/page.php");


class HomePage extends BasePage
{
    public function __construct(?array $user)
    {
        parent::__construct(MenuItem::HOME, $user);
    }

    public function fill_movies(array $movies): Self
    {
        $movieTemplate = new Template('
            <div>
                <p>Nome:</p><p>{{name}}</p>
                <p>Descrizione:</p><p>{{description}}</p>
            </div>
        ');

        $this->replace_var_array_template("main", $movieTemplate, $movies, function ($t, $movie) {
            $t->replace_vars([
                "name" => $movie["name"],
                "description" => $movie["description"]
            ]);
        });

        return $this;
    }
};
