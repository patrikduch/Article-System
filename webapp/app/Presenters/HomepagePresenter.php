<?php

namespace App\Presenters;

use App\Repositories\ProjectDetailRepository;
use App\Services\Authenticator;
use Nette;
use App\Presenters\BasePresenter;

/**
 * Class HomepagePresenter
 * @package App\Presenters
 */
final class HomepagePresenter extends BasePresenter
{
    private $clicked = false;
    /**
     * Handler of async signal event.
     */
    public function handleChangeClickState()
    {
        $this->clicked = !($this->clicked);

        if ($this->isAjax()) {
            $this->redrawControl('clicked_area'); // invalid snippet 'clicked_area'
        }
    }

    /**
     * Renders default view (default.latte).
     */
    public function renderDefault()
    {
        $this->template->clicked = $this->clicked;
    }
}
