<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\IProjectDetailRepository;
use Nette;

/**
 * Class ProjectDetailRepository
 * @package App\Repositories
 */
final class ProjectDetailRepository implements IProjectDetailRepository
{
    private $database;

    /**
     * ProjectDetailRepository constructor.
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Fetch current project name from DBMS.
     * @return Nette\Database\Table\Selection Row of ProjectDetail relation.
     */
    public function getProjectName()
    {
        return $this->database->table('ProjectDetail')->limit(1);
    }
}
