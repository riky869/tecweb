<?php

require_once("generators/page.php");

class LoginPage extends BasePage
{
    public function __construct()
    {
        parent::__construct(MenuItem::LOGIN);
    }

    public function fill_login(): Self
    {
        $t = Template::from_template("login");
        $this->replace_var_template("main", $t);
        return $this;
    }
}
