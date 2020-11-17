<?php

interface IArticleRepository {

    public function getArticle($articleId);
    public function getArticles();
    public function incrementRatingCount($articleId);
}