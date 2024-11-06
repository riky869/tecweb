<?php

require_once("generator/page.php");


class LoginPage extends BasePage
{
    public function __construct()
    {
        parent::__construct($this->load_layout());

        $this->fill_metadata(MenuItem::LOGIN);
        $this->fill_menu(MenuItem::LOGIN);

        // ---------------
        // example inline template
        $t = new Template('<img src="{{img}}" alt="{{alt}}" aria-hidden="true">');
        $t->replace_vars([
            "img" => "",
            "alt" => "",
        ]);

        $this->replace_var("main", $t->get_content());
        // ---------------
    }
}
