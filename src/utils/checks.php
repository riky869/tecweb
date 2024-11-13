<?php

function assert_template_render($content)
{
    $matches = [];
    $m = preg_match('/{{([a-zA-Z0-9_]+)}}/', $content, $matches);
    assert($m == 0, "$m template variables have not been replaced: " . implode(', ', $matches));
}
