<?php

namespace App\Components;

/**
 * Class ArticleListFactoryControlFactory
 * @package App\Components
 */
final class ArticleListControlFactory
{
    /** @var \App\Infrastructure\Repositories\ArticleRepository */
    private $articleRepository;

    /**
     * ArticleListControlFactory constructor.
     * @param \App\Infrastructure\Repositories\ArticleRepository $articleRepository
     */
    public function __construct(\App\Infrastructure\Repositories\ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param $articlePageId
     * @return ArticleListControl
     */
    public function create($articlePageId): ArticleListControl
    {

        if (!isset($articlePageId)) {
            $articlePageId = 1;
        }

        return new ArticleListControl($articlePageId, $this->articleRepository);
    }
}