<?php

require_once("generators/page.php");


class ReviewsPage extends BasePage
{
    public function __construct()
    {
        parent::__construct(MenuItem::REVIEWS);
    }
}
