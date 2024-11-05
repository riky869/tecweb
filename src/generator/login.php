<?php

require_once("generator/page.php");


class LoginPage extends BasePage
{
    public function __construct()
    {
        parent::__construct($this->load_layout());

        $this->fill_metadata_page(MenuItem::LOGIN);
        $this->fill_menu(MenuItem::LOGIN);
    }
}
