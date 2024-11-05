<?php

require_once("utils/db.php");
require_once("generator/reviews.php");

$db = DbConnection::from_env();
$template = new ReviewsPage();

$content = $template->get_content();

echo ($content);
