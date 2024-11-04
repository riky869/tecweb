<?php

require_once("constants.php");


final class Template
{
    private static string $TEMPLATE_DIR = "templates";

    public function __construct() {}

    private function load_template_file(string $name): string
    {
        $content = file_get_contents("{$this::$TEMPLATE_DIR}/{$name}.html");

        return $content;
    }

    private function load_layout(): string
    {
        $content = $this->load_template_file("layout");

        return $content;
    }

    private function replace_var(string $content, string $name, string $value): string
    {
        $content = str_replace("{{{$name}}}", $value, $content);

        return $content;
    }

    private function replace_vars(string $content, array $values): string
    {
        foreach ($values as $name => $value) {
            $content = $this->replace_var($content, $name, $value);
        }

        return $content;
    }

    private function fill_metadata(string $content, string $author, string $description, string $keywords): string
    {
        $content = $this->replace_vars($content, [
            "author" => $author,
            "description" => $description,
            "keywords" => $keywords,
        ]);

        return $content;
    }

    private function fill_metadata_page(string $content, MenuItem $current)
    {
        $item = METADATA[$current->value];

        $content = $this->fill_metadata(
            $content,
            $item["author"],
            $item["description"],
            $item["keywords"],
        );

        return $content;
    }

    private function fill_menu(string $content, MenuItem $current): string
    {
        $link_template = $this->load_template_file("menu_item");
        $current_link_template = $this->load_template_file("menu_current_item");

        $links_html = [];

        foreach (MENU_ITEMS as $item => $link) {
            // start template and replace name
            $template = $current->value == $item ? $current_link_template : $link_template;
            $link_html = $this->replace_var($template, "name", $link["name"]);

            // if is not current link set the href target
            if ($current->value != $item) {
                $link_html = $this->replace_vars($template, [
                    "name" => $link["name"],
                    "url" => $link["url"],
                ]);
            }

            array_push($links_html, $link_html);
        }

        $content = $this->replace_var($content, "links", join("\n", $links_html));

        return  $content;
    }

    public function build_home_page(): string
    {
        $content = $this->load_layout();

        $content = $this->fill_metadata_page($content, MenuItem::HOME);
        $content = $this->fill_menu($content, MenuItem::HOME);

        return $content;
    }

    public function build_login_page(): string
    {
        $content = $this->load_layout();

        $content = $this->fill_metadata_page($content, MenuItem::LOGIN);
        $content = $this->fill_menu($content, MenuItem::LOGIN);

        return $content;
    }

    public function build_review_page(): string
    {
        $content = $this->load_layout();

        $content = $this->fill_metadata_page($content, MenuItem::REVIEWS);
        $content = $this->fill_menu($content, MenuItem::REVIEWS);

        return $content;
    }
}
