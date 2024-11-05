<?php

require_once("generator/template.php");


class BasePage extends Template
{

    public function __construct(string $content)
    {
        parent::__construct($content);
    }

    private function fill_metadata(string $author, string $description, string $keywords)
    {
        $this->replace_vars([
            "author" => $author,
            "description" => $description,
            "keywords" => $keywords,
        ]);
    }

    protected function fill_metadata_page(MenuItem $current)
    {
        $item = METADATA[$current->value];

        $this->fill_metadata(
            $item["author"],
            $item["description"],
            $item["keywords"],
        );
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
