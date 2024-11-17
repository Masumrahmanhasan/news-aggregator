<?php

namespace App;

interface NewsSourceInterface
{
    /**
     * Fetch articles from the news source.
     * @return void
     */
    public function fetchArticles(): void;
}
