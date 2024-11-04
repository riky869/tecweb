<?php

require_once("php/db.php");
require_once("php/template.php");

$db = new DbConnection();
$template = new Template();

$content = $template->build_login_page();

echo ($content);
