<?php

require_once("generators/page.php");


class ReviewsPage extends BasePage
{
    public function __construct()
    {
        parent::__construct($this->load_layout());

        $this->fill_metadata(MenuItem::REVIEWS);
        $this->fill_menu(MenuItem::REVIEWS);
    }
}
