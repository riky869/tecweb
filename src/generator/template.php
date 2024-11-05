<?php

require_once("utils/constants.php");


class Template
{
    private static string $TEMPLATE_DIR = "templates";
    protected string $content = "";

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function from_template(string $name): Self
    {
        $content = $this->load_template_file($name);
        return new Self($content);
    }

    public function get_content(): string
    {
        return $this->content;
    }

    protected function load_template_file(string $name): string
    {
        $content = file_get_contents("{$this::$TEMPLATE_DIR}/{$name}.html");

        return $content;
    }

    protected function load_layout(): string
    {
        $content = $this->load_template_file("layout");

        return $content;
    }

    protected function replace_var(string $name, string $value)
    {
        $this->content = str_replace("{{{$name}}}", $value, $this->content);
    }

    protected function replace_vars(array $values)
    {
        foreach ($values as $name => $value) {
            $this->replace_var($name, $value);
        }
    }
}
