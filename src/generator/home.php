<?php

require_once("generator/page.php");


class HomePage extends BasePage
{
    public function __construct()
    {
        parent::__construct($this->load_layout());

        $this->fill_metadata(MenuItem::HOME);
        $this->fill_menu(MenuItem::HOME);
    }
};
