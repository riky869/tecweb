<?php


enum MenuItem: int
{
    case HOME = 0;
    case REVIEWS = 1;
    case LOGIN = 2;

    public function get_menu(): array
    {
        return MENU_ITEMS[$this->value];
    }

    public function get_metadata(): array
    {
        return METADATA[$this->value];
    }
}

define("MENU_ITEMS", [
    MenuItem::HOME->value => [
        "name" => "Home",
        "url" => "index.php",
    ],
    MenuItem::REVIEWS->value => [
        "name" => "Reviews",
        "url" => "reviews.php",
    ],
    MenuItem::LOGIN->value => [
        "name" => "Login",
        "url" => "login.php",
    ],
]);


// TODO: is this ok?
define("AUTHOR", "TechWeb Castelfranco Veneto");

// TODO: add 50-75 characters keywords
define("METADATA", [
    MenuItem::HOME->value => [
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::LOGIN->value => [
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::REVIEWS->value => [
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
]);
