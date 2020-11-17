<?php

namespace App\Infrastructure\Repositories;

use Nette;

final class ArticleRepository implements \IArticleRepository
{
    private $database;

    /**
     * ArticleRepository constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Fetch list of all articles.
     * @return Nette\Database\ResultSet
     */
    public function getArticles()
    {
        return $this->database->query('SELECT * FROM Article');
    }

    /**
     * Fetch articles based on pagination options.
     * @param $pageId
     * @param $pageSize
     * @return Nette\Database\Table\Selection
     */
    public function getPagedArticles($pageId, $pageSize) {

        $pageCount =  $this->database->table('Article')->count();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setPage($pageId); // číslo aktuální stránky
        $paginator->setItemsPerPage($pageSize); // počet položek na stránce
        $paginator->setItemCount($pageCount); // celkový počet položek, je-li znám

        $result = $this->database->query(
            'SELECT * FROM Article LIMIT ? OFFSET ?',
            $paginator->getLength(),
            $paginator->getOffset()
        );

        return [
            'lastPage' => $paginator->getLastPage(),
            'items' => $result

        ];
    }

    /**
     * Increment rating for specific article.
     * @param $articleId $articleId identifier of target article.
     */
    public function incrementRatingCount($articleId)
    {
        $ratingCount = $this->database->fetchField('SELECT rating FROM Article WHERE id = ?', $articleId);
        $ratingCount++;

        $this->database->query("UPDATE Article SET rating = $ratingCount where id = $articleId;");
    }

    /**
     * Get article by its identifier (Primary key from DBMS).
     * @param $articleId $articleId identifier of target article.
     */
    public function getArticle($articleId)
    {
        return $this->database
            ->fetch('SELECT * FROM Article WHERE id = ?', $articleId);
    }
}