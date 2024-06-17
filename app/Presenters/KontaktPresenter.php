<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class KontaktPresenter extends Nette\Application\UI\Presenter
{
    public function actionDefault()
    {
        $this->template->style = "kontakt";
    }
}
