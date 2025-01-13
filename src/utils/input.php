<?php

function pulisciInput($value)
{
    // elimina gli spazi
    $value = trim($value);
    // rimuove tag html (non sempre è una buona idea!)
    $value = strip_tags($value);
    // converte i caratteri speciali in entità html (ex. &lt;)
    $value = htmlentities($value);
    return $value;
}
