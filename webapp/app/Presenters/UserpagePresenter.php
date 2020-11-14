<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class UserpagePresenter extends Nette\Application\UI\Presenter
{

    private $database;


    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function renderDefault() {
        
    }
}
