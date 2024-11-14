<?php


enum MenuItem: int
{
    case HOME = 0;
    case CATEGORIES = 1;
    case FILM = 2;
    case LOGIN = 3;
    case SIGNUP = 4;
    case ABOUT = 5;
    case PROFILE = 6;
    case USERS = 7;
    case FILMS = 8;

    public function get_menu(): array
    {
        return MENU_ITEMS[$this->value];
    }

    public function get_metadata(): array
    {
        return METADATA[$this->value];
    }

    public function get_breadcrumb(): array
    {
        return BREADCRUMB_ITEMS[$this->value];
    }
}

define("MENU_ITEMS", [
    MenuItem::HOME->value => [
        "name" => "Home",
        "url" => "index.php",
        "attr" => 'lang="it"',
        "admin" => false,
        "show" => true,
    ],
    MenuItem::CATEGORIES->value => [
        "name" => "Categorie",
        "url" => "categories.php",
        "attr" => "",
        "admin" => false,
        "show" => true,
    ],
    MenuItem::USERS->value => [
        "name" => "Utenti",
        "url" => "users.php",
        "attr" => "",
        "admin" => true,
        "show" => true,
    ],
    MenuItem::ABOUT->value => [
        "name" => "About",
        "url" => "about.php",
        "attr" => "",
        "admin" => false,
        "show" => true,
    ],
    MenuItem::FILMS->value => [
        "url" => "films.php",
        "show" => false,
    ],
    MenuItem::FILM->value => [
        "url" => "film.php",
        "show" => false,
    ],
    MenuItem::PROFILE->value => [
        "url" => "profile.php",
        "show" => false,
    ],
    MenuItem::SIGNUP->value => [
        "url" => "signup.php",
        "show" => false,
    ],
]);

define("BREADCRUMB_ITEMS", [
    MenuItem::HOME->value => [
        "before" => [],
        "last" => "Home"
    ],
    MenuItem::LOGIN->value => [
        "before" => [MenuItem::HOME],
        "last" => "Login"
    ],
    MenuItem::CATEGORIES->value => [
        "before" => [MenuItem::HOME],
        "last" => "Categorie"
    ],
    MenuItem::SIGNUP->value => [
        "before" => [MenuItem::HOME],
        "last" => "Registrazione"
    ],
    MenuItem::ABOUT->value => [
        "before" => [MenuItem::HOME],
        "last" => "Chi siamo"
    ],
    MenuItem::PROFILE->value => [
        "before" => [MenuItem::HOME],
        "last" => "Profilo"
    ],
    MenuItem::USERS->value => [
        "before" => [MenuItem::HOME],
        "last" => "Utenti"
    ],
]);


// TODO: is this ok?
define("AUTHOR", "TechWeb Castelfranco Veneto");

// TODO: add 50-75 characters keywords
define("METADATA", [
    MenuItem::HOME->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::LOGIN->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::CATEGORIES->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::SIGNUP->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::FILM->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::ABOUT->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::PROFILE->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
    MenuItem::USERS->value => [
        "title" => "Movies",
        "author" => AUTHOR,
        "description" => "Pagina dedicata alle recensioni dei migliori film",
        "keywords" => "film review",
    ],
]);
