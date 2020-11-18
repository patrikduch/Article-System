<?php

namespace App\Components;

use App\Infrastructure\Repositories\ArticleRepository;
use Nette\Application\UI\Control;

/**
 * Class ArticleListControl
 * @package App\Components
 */
final class ArticleListControl extends Control {

    /** @var ArticleRepository */
    private $articleRepository;

    private $articlePageId;

    public function __construct($articlePageId, ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->articlePageId = $articlePageId;
    }

    /**
     * Render list of articles.
     */
    public function render() {

        $articlePageId = $this->getParameter('pageId');
        $result = $this->articleRepository->getPagedArticles($this->articlePageId, 4);
        $this->template->articles = $result['items'];
        $this->template->articlesLastPage = $result['lastPage'];

        $this->template->render(__DIR__ . '/ArticleListControl.latte');
    }

    /**
     * Handler of async signal event.
     * @param $articleId
     */
    public function handleChangeClickState($articleId)
    {
        $this->articleRepository->incrementRatingCount($articleId);

        $this->redrawControl('clicked_area'); // invalid snippet 'clicked_area'

    }

}


