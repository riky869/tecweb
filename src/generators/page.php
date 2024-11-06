<?php

require_once("generators/template.php");


class BasePage extends Template
{

    public function __construct(string $content)
    {
        parent::__construct($content);
    }

    protected function fill_metadata(MenuItem $current)
    {
        $item = METADATA[$current->value];

        $this->replace_vars([
            "author" => $item["author"],
            "description" => $item["description"],
            "keywords" => $item["keywords"],
        ]);
    }

    protected function fill_menu(MenuItem $current)
    {
        $link_template = $this->load_template_file("menu_item");
        $current_link_template = $this->load_template_file("menu_current_item");
        $links_html = [];

        foreach (MENU_ITEMS as $item => $link) {
            $vars = ["name" => $link["name"]];

            // if is not current link set the href target
            if ($current->value != $item) {
                $template = new Template($link_template);
                $vars["url"] = $link["url"];
            } else {
                $template = new Template($current_link_template);
            }

            $template->replace_vars($vars);

            array_push($links_html, $template->get_content());
        }

        $this->replace_var("links", join("\n", $links_html));
    }
};
