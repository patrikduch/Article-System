<?php

namespace App\Presenters;

use App\Infrastructure\Repositories\ArticleRepository;
use App\Services\Authenticator;
use Nette;
use App\Presenters\BasePresenter;

/**
 * Class HomepagePresenter
 * @package App\Presenters
 */
final class HomepagePresenter extends BasePresenter
{

    /** @var ArticleRepository @inject */
    public $articleRepository;

    /**
     * Handler of async signal event.
     */
    public function handleChangeClickState($articleId)
    {
        $this->articleRepository->incrementRatingCount($articleId);

        if ($this->isAjax()) {
            $this->redrawControl('clicked_area'); // invalid snippet 'clicked_area'
        }
    }

    /**
     * Renders default view (default.latte).
     */
    public function renderDefault()
    {
        $articlePageId = $this->getParameter('pageId');

        if (is_null($articlePageId)) {
            $articlePageId = 1;
        }

        $result = $this->articleRepository->getPagedArticles($articlePageId, 4);

        $this->template->articles = $result['items'];
        $this->template->articlesLastPage = $result['lastPage'];
    }

    /**
     * Renders detail of chosen article.
     * @param $articleId $articleId identifier of target article.
     */
    public function renderArticleDetail($articleId) {
        $this->template->articleContent = $this->articleRepository->getArticle($articleId);
    }

}
