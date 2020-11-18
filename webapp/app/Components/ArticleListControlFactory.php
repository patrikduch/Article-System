<?php

namespace App\Components;

use \App\Infrastructure\Repositories\ArticleRepository;

/**
 * Class ArticleListFactoryControlFactory
 * @package App\Components
 */
final class ArticleListControlFactory
{
    /** @var ArticleRepository $articleRepository  Data repository that enables article management, */
    private $articleRepository;

    /**
     * ArticleListControlFactory constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Creation new article list components.
     * @param $articlePageId $articlePageId  Page identifier of article listing.
     * @return ArticleListControl Article list component.
     */
    public function create($articlePageId): ArticleListControl
    {
        // Path doesnt have pageId in querystring, so its needed to set articlePage to 1
        // Starting point = 1, firstpage
        if (!isset($articlePageId)) {
            $articlePageId = 1;
        }
        return new ArticleListControl($articlePageId, $this->articleRepository);
    }
}