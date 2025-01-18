<?php

function clean_input($value): mixed
{
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}
