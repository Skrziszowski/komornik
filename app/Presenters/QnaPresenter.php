<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class QnAPresenter extends Nette\Application\UI\Presenter
{
    public function actionDefault()
    {
        $this->template->style = "qna";
    }
}
