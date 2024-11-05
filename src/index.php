<?php

require_once("utils/db.php");
require_once("generator/home.php");

$db = DbConnection::from_env();
$template = new HomePage();

$content = $template->get_content();

echo ($content);
