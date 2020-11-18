<?php

namespace App\Components;

use App\Infrastructure\Repositories\ArticleRepository;
use Nette\Application\UI\Control;

/**
 * Class ArticleListControl
 * @package App\Components
 */
final class ArticleListControl extends Control {

    /** @var ArticleRepository $articleRepository  Data repository that enables article management, */
    private $articleRepository;

    /**
     * @var $articlePageId $articlePageId Article page identifier that is used for rendering inside each
     * Article list instance.
     */
    private $articlePageId;

    /**
     * ArticleListControl constructor.
     * @param $articlePageId $articlePageId Article page identifier.
     * @param ArticleRepository $articleRepository
     * methods for Article entity.
     */
    public function __construct($articlePageId, ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->articlePageId = $articlePageId;
    }

    /**
     * Render list of articles.
     */
    public function render() {
        $result = $this->articleRepository->getPagedArticles($this->articlePageId, 4);

        $this->template->articles = $result['items'];
        $this->template->articlesLastPage = $result['lastPage'];
        $this->template->render(__DIR__ . '/ArticleListControl.latte');
    }

    /**
     * Async event for incrementing rating of selected article.
     * @param $articleId $articleId Identifier of passed article.
     */
    public function handleIncrementRating($articleId)
    {
        $this->articleRepository->incrementRatingCount($articleId);
        $this->redrawControl('articlesListContainer'); // invalid snippet 'articlesListContainer'
    }

}


