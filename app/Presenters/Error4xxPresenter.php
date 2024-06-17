<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;

final class Error4xxPresenter extends Nette\Application\UI\Presenter
{
	protected function checkHttpMethod(): void
	{
		if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
			$this->error();
		}
	}

	public function renderDefault(Nette\Application\BadRequestException $exception): void
	{
		$code = $exception->getCode();
		$file = is_file($file = __DIR__ . "/templates/Error/$code.latte")
			? $file
			: __DIR__ . '/templates/Error/4xx.latte';
		$this->template->httpCode = $code;
		$this->template->setFile($file);
	}
}
