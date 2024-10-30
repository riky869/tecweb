<?php

include_once("php/db.php");

$db = new DbConnection();

$content = file_get_contents("html/home.html");

echo ($content);
