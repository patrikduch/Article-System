<?php

namespace App\Presenters;

use App\Components\ArticleListControl;
use App\Components\ArticleListControlFactory;
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

    /** @var ArticleListControlFactory $articleListFactoryControl
     * factory for creating new instances of ArticleListControl.
     */
    private $articleListFactoryControl;

    /**
     * HomepagePresenter constructor.
     * @param ArticleListControlFactory $articleListFactoryControl
     */
    public function __construct(ArticleListControlFactory $articleListFactoryControl)
    {
        $this->articleListFactoryControl = $articleListFactoryControl;
    }

    /**
     * Renders default view (default.latte).
     * @param $pageId $pageId Needed page identifier argument that is being passed into sub component
     * "ArticleListControl".
     */
    public function renderDefault($pageId)
    {
    }

    /**
     * Renders detail of chosen article.
     * @param $articleId $articleId identifier of target article.
     */
    public function renderArticleDetail($articleId) {
        $this->template->articleContent = $this->articleRepository->getArticle($articleId);
    }

    // ---------- Components ---------

    /**
     * Create Article list component that encapsulated all needed functionality for article listing.
     * @return ArticleListControl Component with article listing functionality.
     */
    protected function createComponentArticleList(): ArticleListControl
    {
        $articlePageId = $this->getParameter('pageId');

        return $this->articleListFactoryControl->create($articlePageId);
    }

}
