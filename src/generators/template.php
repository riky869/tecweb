<?php

require_once("utils/constants.php");
require_once("utils/checks.php");


class Template
{
    private static string $TEMPLATE_DIR = "templates";
    protected string $content = "";

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function get_content(): string
    {
        return $this->content;
    }

    public function show(): Self
    {
        // assert that the template have replaced all the variables
        assert_template_render($this->content);
        echo ($this->get_content());

        return $this;
    }

    public function copy(): Self
    {
        return new Self($this->content);
    }

    public static function from_content(string $content): Self
    {
        return new Self($content);
    }

    public static function from_template(string $name): Self
    {
        $content = Self::load_template_file($name);
        return Self::from_content($content);
    }

    protected static function load_template_file(string $name): string
    {
        $base_dir = Self::$TEMPLATE_DIR;
        $content = file_get_contents("{$base_dir}/{$name}.html");

        return $content;
    }

    protected static function load_layout(): string
    {
        $content = Self::load_template_file("layout");

        return $content;
    }

    public function replace_var(string $name, string $value): Self
    {
        $this->content = str_replace("{{{$name}}}", $value, $this->content);
        return $this;
    }

    public function replace_var_template(string $name, Self $template): Self
    {
        $this->replace_var($name, $template->get_content());
        return $this;
    }

    public function replace_var_array_template(string $name, Self $template, array $values, callable $func): Self
    {
        $contents = array_map(function ($value) use ($template, $func) {
            $t = $template->copy();
            $func($t, $value);
            return $t->get_content();
        }, $values);
        $content = join("\n", $contents);
        $this->replace_var($name, $content);

        return $this;
    }

    public function replace_vars(array $values): Self
    {
        $pattern = implode("|", array_keys($values));

        while (preg_match("/\{\{($pattern)\}\}/", $this->content)) {
            foreach ($values as $name => $value) {
                $this->replace_var($name, $value);
            }
        }

        return $this;
    }

    public function delete_var(string $name): Self
    {
        $this->replace_var($name, "");

        return $this;
    }

    public function delete_vars(array $names): Self
    {
        foreach ($names as $name) {
            $this->delete_var($name);
        }

        return $this;
    }
}
