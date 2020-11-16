<?php

interface IArticleRepository {

    public function getArticles();
    public function incrementRatingCount($articleId);
}