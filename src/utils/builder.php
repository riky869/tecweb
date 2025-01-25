<?php

require_once("utils/cred.php");

enum VarType
{
    case Section; // <!--$name-->
    case Single; // {{$name}}
    case Block; // <!--$name_start--> ... <!--$name_end-->
}

class Builder
{
    private static string $TEMPLATE_DIR = "templates";
    protected string $content = "";
    protected string $name;

    public function __construct(string $content, string $name)
    {
        $this->content = $content;
        $this->name = $name;
    }

    public function get_content(): string
    {
        return $this->content;
    }

    public function show(): Self
    {
        // assert that the template have replaced all the variables
        $matches = [];
        $m = preg_match_all("/({{([a-zA-Z0-9_]+)}}|<!--([a-zA-Z0-9_]+)-->)/", $this->content, $matches, PREG_PATTERN_ORDER);
        if (DEFAULT_VARS["DEBUG"] ?? false) {
            assert($m == 0, "$m template variables have not been replaced: " . join(', ', $matches[0]));
        }

        echo ($this->content);

        return $this;
    }

    public function copy(): Self
    {
        return new Self($this->content, $this->name);
    }

    public static function from_content(string $content, string $name): Self
    {
        return new Self($content, $name);
    }

    public static function from_template(string $name): Self
    {
        $content = Self::load_template_file($name);
        return Self::from_content($content, $name);
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

    public function replace_var_arr(string $sec_name, array $values, Builder $sec, $func, VarType $var_type): Self
    {
        $content = join("\n", array_map(
            function ($i) use ($func, $sec) {
                $sec = $sec->copy();
                $sec  = $func($sec, $i) ?? $sec;
                return $sec->get_content();
            },
            $values
        ));

        return $this->replace_var($sec_name, $content, $var_type);
    }

    public function replace_block_name_arr(string $sec_name, array $values, $func): Self
    {
        return $this->replace_var_arr($sec_name, $values, $this->get_block($sec_name), $func, VarType::Block);
    }
    public function show_block(string $sec_name): Self
    {
        return $this->replace_var($sec_name, $this->get_block($sec_name), VarType::Block);
    }

    public function get_block(string $name): Self
    {
        $start_pattern = "<!--{$name}_start-->";
        $end_pattern = "<!--{$name}_end-->";

        $start = strpos($this->content, $start_pattern);
        $end = strpos($this->content, $end_pattern);

        assert($start !== false && $end !== false);

        $content = substr($this->content, $start + strlen($start_pattern), $end - $start - strlen($start_pattern));
        return Self::from_content($content, "");
    }

    public function replace_var(string $name, mixed $value, VarType $type = VarType::Single): Self
    {
        $value = $value instanceof Self ? $value->get_content() : $value;

        if ($type == VarType::Block) {
            $start_pattern = "<!--{$name}_start-->";
            $end_pattern = "<!--{$name}_end-->";

            $start = strpos($this->content, $start_pattern);
            $end = strpos($this->content, $end_pattern);

            // pattern not found
            if ($start === false && $end === false) {
                return $this;
            }
            assert($start !== false && $end !== false);

            $this->content = substr_replace($this->content, $value, $start, $end - $start + strlen($start_pattern));
            return $this;
        }

        $pattern = match ($type) {
            VarType::Single =>  "{{{$name}}}",
            VarType::Section => "<!--$name-->",
        };
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

    public function replace_singles(array $values): Self
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

    public function delete_var(string $name, VarType $type): Self
    {
        $this->replace_var($name, "", $type);

        return $this;
    }

    public function delete_singles(array $names): Self
    {
        foreach ($names as $name) {
            $this->delete_var($name, VarType::Single);
        }

        return $this;
    }

    public function delete_blocks(array $names): Self
    {
        foreach ($names as $name) {
            $this->delete_var($name, VarType::Block);
        }

        return $this;
    }

    public function delete_secs(array $names): Self
    {
        foreach ($names as $name) {
            $this->delete_var($name, VarType::Section);
        }

        return $this;
    }

    public function replace_profile(?array $user, Self $common): Self
    {
        if ($user) {
            $this->replace_secs([
                "profile" => $common->get_block("user_logged")->replace_singles([
                    "username" => $user["username"],
                ]),
            ]);
        } else {
            $this->replace_secs([
                "profile" => $common->get_block("user_not_logged"),
            ]);
        }

        return $this;
    }

    public function build(?array $user, Self $common): Self
    {
        $base_path = "";
        if (!empty(DEFAULT_VARS["DB_USER"])) {
            $base_path .= "/" . DEFAULT_VARS["DB_USER"];
        }
        $base_path .= "/" . $this->name;

        $this->replace_secs([
            "header" => $common->get_block("header"),
            "torna_su" => $common->get_block("torna_su"),
            "footer" => $common->get_block("footer"),
            "hamburger" => $common->get_block("hamburger"),
            // NOTE: this is a workaround to use relative path based on the environment, production UniPD server or local docker env
            "html_head" => $common->get_block("html_head")->replace_var("html_head_base_path", $base_path),
        ]);

        $this->replace_profile($user, $common);

        if (empty($user) || !$user["is_admin"])
            $this->delete_var("menu_admin", VarType::Block);
        else
            $this->replace_var("menu_admin", $this->get_block("menu_admin"), VarType::Block);

        return $this;
    }
}
