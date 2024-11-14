<?php

require_once("generators/page.php");


class CategoriesPage extends BasePage
{
    public function __construct(?array $user)
    {
        parent::__construct(MenuItem::CATEGORIES, $user);
    }
}
