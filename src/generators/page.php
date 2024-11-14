<?php

require_once("generators/template.php");


class BasePage extends Template
{
    private MenuItem $page;
    private ?array $user;

    public function __construct(MenuItem $page, ?array $user)
    {
        parent::__construct($this->load_layout());
        $this->page = $page;
        $this->user = $user;

        $this->fill_metadata()->fill_menu()->fill_breadcrumb()->fill_profile();
    }

    public function fill_profile(): Self
    {
        if ($this->user) {
            $t = Template::from_content('
                <div>
                    <p>Utente corrente: {{username}}</p>
                    <form method="POST" action="logout.php">
                        <input type="submit" value="logout">
                    </form>
                </div>
            ')
                ->replace_var("username", $this->user["username"]);
        } else {
            $t = Template::from_content('
                <a href="login.php">Login Page</a>
            ');
        }

        $this->replace_var_template("profile", $t);
        return $this;
    }

    protected function fill_metadata(): Self
    {
        $item = $this->page->get_metadata();

        $this->replace_vars($item);

        return $this;
    }

    protected function fill_breadcrumb(): Self
    {
        // check if this is a static breadcrumb that can be handled with this function
        if (array_search($this->page->value, array_keys(BREADCRUMB_ITEMS)) === false) return $this;

        $item = $this->page->get_breadcrumb();
        $links =  array_map(function ($i) {
            $url = MENU_ITEMS[$i->value]["url"];
            $name = MENU_ITEMS[$i->value]["name"];

            return "<a href=\"$url\">$name</a>";
        }, $item["before"]);

        $t = Template::from_content('
            {{links}}{{last}}
        ')->replace_vars([
            "links" => join(" >> ", $links),
            "last" => (count($links) == 0 ? "" : " >> ") .  $item["last"],
        ]);

        $this->replace_var_template("breadcrumb", $t);
        return $this;
    }

    protected function fill_menu(): Self
    {
        $contents = [];

        foreach (MENU_ITEMS as $item => $link) {
            if (!$link["show"]) continue;
            if ($link["admin"] && !($this->user["is_admin"] ?? false)) continue;

            // if is not current link set the href target
            $template = Self::from_content('<li {{attr}}>{{name}}</li>');
            if ($this->page->value != $item) {
                $link["name"] = '<a href="{{url}}">' . $link["name"] . '</a>';
                $link["attr"] .= ' id="currentPage"';
            }

            $contents[] = $template->replace_vars($link)->get_content();
        }

        $this->replace_var("links", join("\n", $contents));

        return $this;
    }
};
