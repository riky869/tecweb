<?php

enum VarType
{
    case Section;
    case Single;
}

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
        $m = preg_match_all("/({{([a-zA-Z0-9_]+)}}|<!--([a-zA-Z0-9_]+)-->)/", $this->content, $matches);
        assert($m == 0, "$m template variables have not been replaced: " . join(', ', $matches));

        echo ($this->content);

        return $this;
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

    public static function load_common(): Self
    {
        return Self::from_template("common");
    }

    public static function load_template_file(string $name): string
    {
        $base_dir = Self::$TEMPLATE_DIR;
        $name = str_replace(".php", "", $name);
        $content = file_get_contents("{$base_dir}/{$name}.html");

        return $content;
    }

    public function get_sec(string $name): Self
    {
        $start_pattern = "<!--{$name}_start-->";
        $end_pattern = "<!--{$name}_end-->";

        $start = strpos($this->content, $start_pattern);
        $end = strpos($this->content, $end_pattern);

        assert($start !== false && $end !== false);

        $content = substr($this->content, $start + strlen($start_pattern), $end - $start - strlen($start_pattern));
        return Self::from_content($content);
    }

    public function replace_var(string $name, string $value, VarType $type = VarType::Single): Self
    {
        $pattern = match ($type) {
            VarType::Single =>  "{\{$name\}}",
            VarType::Section => "<!--$name-->",
        };
        $value = $value instanceof Self ? $value->get_content() : $value;
        $this->content = str_replace($pattern, $value, $this->content);
        return $this;
    }

    public function replace_secs(array $values): Self
    {
        $pattern = join("|", array_keys($values));
        $pattern = "/<!--($pattern)-->/";

        while (preg_match_all($pattern, $this->content)) {
            foreach ($values as $name => $value) {
                $this->replace_var($name, $value, VarType::Section);
            }
        }

        return $this;
    }

    public function replace_vars(array $values): Self
    {
        $pattern = join("|", array_keys($values));
        $pattern = "/{{($pattern)}}/";

        while (preg_match_all($pattern, $this->content)) {
            foreach ($values as $name => $value) {
                $this->replace_var($name, $value, VarType::Single);
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
        $this->replace_var($name, "", VarType::Section);

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
