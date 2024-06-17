<?php

namespace App\Forms;
use Nette\Application\UI\Form;

class FormValidator
{
    public static function validateBirthDate(Form $form, \stdClass $data): void
    {
        $minAgeDate = (new \DateTime())->sub(new \DateInterval('P6Y'));
        $maxAgeDate = (new \DateTime())->sub(new \DateInterval('P120Y'));
        $userBirthDate = $data->dayB->getTimestamp();

        if ($userBirthDate > $minAgeDate->getTimestamp()) {
            $form['dayB']->addError('Minimální stáří - 6 let');
        } elseif ($userBirthDate < $maxAgeDate->getTimestamp()) {
            $form['dayB']->addError('Maximální stáří - 120 let');
        }
    }
}