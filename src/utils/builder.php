<?php

class Builder
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
        $matches = [];
        $m = preg_match('/{{([a-zA-Z0-9_]+)}}/', $this->content, $matches);
        assert($m == 0, "$m template variables have not been replaced: " . join(', ', $matches));

        echo ($this->content);

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

    public function replace_sec(string $name, string $content): Self
    {
        $start = strpos($this->content, "<!-- $name\\_start -->");
        $end = strpos($this->content, "<!-- $name\\_end -->");

        assert($start !== 0 && $end !== 0);

        $this->content = substr_replace($this->content, $content, $start, $end - $start);

        return $this;
    }

    public function replace_vars(array $values): Self
    {
        $pattern = join("|", array_keys($values));

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

    public function delete_sec(string $name): Self
    {
        $this->replace_sec($name, "");

        return $this;
    }

    public function delete_secs(array $names): Self
    {
        foreach ($names as $name) {
            $this->delete_sec($name);
        }

        return $this;
    }

    public function build(): Self
    {


        return $this;
    }
}
